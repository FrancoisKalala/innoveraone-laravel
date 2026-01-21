<?php

namespace App\Livewire\Group;

use App\Models\Group;
use App\Models\GroupMessage;
use App\Models\User;
use Livewire\Component;

class GroupDetail extends Component
{
    public $selectedGroup = null;
    protected $messages = [];
    public $newMessage = '';
    public $showCreateModal = false;
    public $groupName = '';
    public $groupDescription = '';
    public $editingMessageId = null;
    public $editedContent = '';
    public $showMessageInfo = null;
    public $infoMessageDetails = [];
    public $showEditGroupModal = false;
    public $editGroupName = '';
    public $editGroupDescription = '';
    public $editGroupPrivacy = '';
    public $showManageMembersModal = false;
    public $searchMemberQuery = '';
    public $searchMemberResults = [];
    public $groupMembers = [];

    public function mount()
    {
        // Don't load groups here - just initialize
        $this->selectedGroup = null;
    }

    public function selectGroup($groupId)
    {
        $this->selectedGroup = Group::find($groupId);
        if ($this->selectedGroup) {
            $this->loadMessages();
        }
    }

    public function loadMessages()
    {
        if (!$this->selectedGroup) {
            $this->messages = [];
            return;
        }

        $this->messages = GroupMessage::where('group_id', $this->selectedGroup->id)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function sendGroupMessage()
    {
        if (empty(trim($this->newMessage)) || !$this->selectedGroup) {
            return;
        }

        GroupMessage::create([
            'group_id' => $this->selectedGroup->id,
            'user_id' => auth()->id(),
            'content' => $this->newMessage,
        ]);

        $this->newMessage = '';
        $this->loadMessages();
    }

    public function openCreateModal()
    {
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->groupName = '';
        $this->groupDescription = '';
    }

    public function createGroup()
    {
        $this->validate([
            'groupName' => 'required|string|min:3|max:100',
        ]);

        $group = Group::create([
            'created_by' => auth()->id(),
            'name' => $this->groupName,
            'description' => $this->groupDescription ?? '',
            'slug' => \Illuminate\Support\Str::slug($this->groupName . '-' . uniqid()),
            'privacy' => 'private',
        ]);

        $group->members()->attach(auth()->id());

        $this->selectedGroup = $group;
        $this->closeCreateModal();
        $this->loadMessages();
    }


    public function deleteMessage($messageId)
    {
        $message = GroupMessage::find($messageId);
        
        if (!$message) {
            return;
        }

        // Only allow the message sender or group admin to delete
        if (auth()->id() === $message->user_id || auth()->id() === $this->selectedGroup->created_by) {
            $message->delete();
            $this->loadMessages();
        }
    }

    public function startEditMessage($messageId, $content)
    {
        $message = GroupMessage::find($messageId);
        
        if (!$message || auth()->id() !== $message->user_id) {
            return;
        }

        $this->editingMessageId = $messageId;
        $this->editedContent = $content;
    }

    public function cancelEditMessage()
    {
        $this->editingMessageId = null;
        $this->editedContent = '';
    }

    public function saveEditedMessage()
    {
        if (empty(trim($this->editedContent)) || !$this->editingMessageId) {
            return;
        }

        $message = GroupMessage::find($this->editingMessageId);
        
        if (!$message || auth()->id() !== $message->user_id) {
            return;
        }

        $message->update([
            'content' => $this->editedContent,
        ]);

        $this->cancelEditMessage();
        $this->loadMessages();
    }

    public function showMessageDetails($messageId)
    {
        $message = GroupMessage::find($messageId);
        
        if (!$message) {
            return;
        }

        $this->showMessageInfo = $messageId;
        $this->infoMessageDetails = [
            'id' => $message->id,
            'sender' => $message->user->name,
            'content' => $message->content,
            'created_at' => $message->created_at->format('M d, Y - H:i:s'),
            'updated_at' => $message->updated_at->format('M d, Y - H:i:s'),
            'is_edited' => $message->updated_at->notEqualTo($message->created_at),
        ];
    }

    public function closeMessageInfo()
    {
        $this->showMessageInfo = null;
        $this->infoMessageDetails = [];
    }

    public function openEditGroupModal()
    {
        if (!$this->selectedGroup || !$this->canManageGroup()) {
            return;
        }

        $this->showEditGroupModal = true;
        $this->editGroupName = $this->selectedGroup->name;
        $this->editGroupDescription = $this->selectedGroup->description ?? '';
        $this->editGroupPrivacy = $this->selectedGroup->privacy;
    }

    public function closeEditGroupModal()
    {
        $this->showEditGroupModal = false;
        $this->editGroupName = '';
        $this->editGroupDescription = '';
        $this->editGroupPrivacy = '';
    }

    public function saveGroupInfo()
    {
        if (!$this->selectedGroup || !$this->canManageGroup()) {
            return;
        }

        $this->validate([
            'editGroupName' => 'required|string|min:3|max:100',
            'editGroupDescription' => 'nullable|string|max:500',
            'editGroupPrivacy' => 'required|in:public,private',
        ]);

        $this->selectedGroup->update([
            'name' => $this->editGroupName,
            'description' => $this->editGroupDescription,
            'privacy' => $this->editGroupPrivacy,
        ]);

        $this->closeEditGroupModal();
    }

    public function canManageGroup()
    {
        if (!$this->selectedGroup) {
            return false;
        }

        // Only creator can manage
        return auth()->id() === $this->selectedGroup->created_by;
    }

    public function deleteGroup()
    {
        if (!$this->selectedGroup || !$this->canManageGroup()) {
            return;
        }

        $this->selectedGroup->delete();
        $this->selectedGroup = null;
        session()->flash('success', 'Group deleted successfully');
    }

    public function openManageMembersModal()
    {
        if (!$this->selectedGroup || !$this->canManageGroup()) {
            return;
        }

        $this->showManageMembersModal = true;
        $this->loadGroupMembers();
    }

    public function closeManageMembersModal()
    {
        $this->showManageMembersModal = false;
        $this->searchMemberQuery = '';
        $this->searchMemberResults = [];
    }

    public function loadGroupMembers()
    {
        if (!$this->selectedGroup) {
            $this->groupMembers = [];
            return;
        }

        // Normalize members to expected array structure: ['user' => [...], 'role' => '...']
        $this->groupMembers = $this->selectedGroup->members()
            ->get()
            ->map(function ($user) {
                return [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                    ],
                    'role' => $user->pivot->role,
                ];
            })
            ->toArray();
    }

    public function updatedSearchMemberQuery()
    {
        if (strlen($this->searchMemberQuery) < 2) {
            $this->searchMemberResults = [];
            return;
        }

        // Search all users
        $this->searchMemberResults = User::where(function ($query) {
            $query->where('name', 'like', '%' . $this->searchMemberQuery . '%')
                  ->orWhere('username', 'like', '%' . $this->searchMemberQuery . '%');
        })
        ->where('id', '!=', auth()->id())
        ->whereNotIn('id', $this->selectedGroup->members()->pluck('users.id')->toArray())
        ->limit(10)
        ->get();
    }

    public function addMember($userId)
    {
        if (!$this->selectedGroup || !$this->canManageGroup()) {
            return;
        }

        $user = User::find($userId);
        if (!$user) {
            return;
        }

        // Add member with 'member' role
        $this->selectedGroup->members()->attach($userId, ['role' => 'member']);
        
        $this->loadGroupMembers();
        $this->searchMemberQuery = '';
        $this->searchMemberResults = [];
    }

    public function removeMember($userId)
    {
        if (!$this->selectedGroup || !$this->canManageGroup()) {
            return;
        }

        // Prevent removing the group creator
        if ($userId === $this->selectedGroup->created_by) {
            session()->flash('error', 'Cannot remove the group creator');
            return;
        }

        $this->selectedGroup->members()->detach($userId);
        $this->loadGroupMembers();
    }

    public function promoteToAdmin($userId)
    {
        if (!$this->selectedGroup || !$this->canManageGroup()) {
            return;
        }

        $this->selectedGroup->members()->updateExistingPivot($userId, ['role' => 'admin']);
        $this->loadGroupMembers();
    }

    public function demoteFromAdmin($userId)
    {
        if (!$this->selectedGroup || !$this->canManageGroup()) {
            return;
        }

        // Prevent demoting the group creator
        if ($userId === $this->selectedGroup->created_by) {
            session()->flash('error', 'Cannot change the group creator role');
            return;
        }

        $this->selectedGroup->members()->updateExistingPivot($userId, ['role' => 'member']);
        $this->loadGroupMembers();
    }

    public function render()
    {
        return view('livewire.group.group-detail', [
            'messages' => $this->messages,
            // Use the normalized groupMembers array prepared in loadGroupMembers()
            'groupMembers' => $this->groupMembers,
        ]);
    }
}
