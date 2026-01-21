<?php

namespace App\Livewire\Group;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupMessage;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class GroupManager extends Component
{
    use WithPagination;

    public $groups;
    public $groupName = '';
    public $groupDescription = '';
    public $selectedGroup;
    public $groupMembers = [];
    public $groupMessages = [];
    public $messageContent = '';
    public $showCreateForm = false;

    public function mount()
    {
        $this->loadGroups();
    }

    public function loadGroups()
    {
        $this->groups = auth()->user()->groups()
            ->with('members')
            ->paginate(10);
    }

    public function createGroup()
    {
        $this->validate([
            'groupName' => 'required|string|min:3|max:100',
            'groupDescription' => 'nullable|string|max:500',
        ]);

        $group = Group::create([
            'created_by' => auth()->id(),
            'name' => $this->groupName,
            'description' => $this->groupDescription,
            'slug' => \Illuminate\Support\Str::slug($this->groupName . '-' . uniqid()),
            'privacy' => 'private',
        ]);

        GroupMember::create([
            'group_id' => $group->id,
            'user_id' => auth()->id(),
            'role' => 'admin',
        ]);

        $this->reset(['groupName', 'groupDescription', 'showCreateForm']);
        $this->loadGroups();
        session()->flash('success', 'Group created successfully!');
    }

    public function selectGroup($groupId)
    {
        $this->selectedGroup = Group::find($groupId);
        $this->groupMembers = $this->selectedGroup->members;
        $this->loadGroupMessages();
    }

    public function loadGroupMessages()
    {
        $this->groupMessages = GroupMessage::where('group_id', $this->selectedGroup->id)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->paginate(30);
    }

    public function sendGroupMessage()
    {
        if (!$this->selectedGroup) {
            return;
        }

        $this->validate([
            'messageContent' => 'required|string|min:1|max:5000',
        ]);

        GroupMessage::create([
            'group_id' => $this->selectedGroup->id,
            'user_id' => auth()->id(),
            'content' => $this->messageContent,
            'category' => 'text',
        ]);

        $this->messageContent = '';
        $this->loadGroupMessages();
        $this->dispatch('messageSent');
    }

    public function addMember($userId)
    {
        if (!$this->selectedGroup->isAdmin(auth()->user())) {
            session()->flash('error', 'Only admins can add members!');
            return;
        }

        GroupMember::updateOrCreate(
            [
                'group_id' => $this->selectedGroup->id,
                'user_id' => $userId,
            ],
            ['role' => 'member']
        );

        $this->loadGroups();
        session()->flash('success', 'Member added!');
    }

    public function removeMember($userId)
    {
        if (!$this->selectedGroup->isAdmin(auth()->user())) {
            session()->flash('error', 'Only admins can remove members!');
            return;
        }

        GroupMember::where('group_id', $this->selectedGroup->id)
            ->where('user_id', $userId)
            ->delete();

        $this->loadGroups();
        session()->flash('success', 'Member removed!');
    }

    public function render()
    {
        return view('livewire.group.group-manager');
    }
}
