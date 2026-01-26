
import { Picker } from 'emoji-mart';
try {
    require('emoji-mart/css/emoji-mart.css');
} catch (e) {
    // fallback: dynamically inject CSS if import fails (Vite/ESBuild issue)
    const cssId = 'emoji-mart-css';
    if (!document.getElementById(cssId)) {
        const link = document.createElement('link');
        link.id = cssId;
        link.rel = 'stylesheet';
        link.type = 'text/css';
        link.href = '/node_modules/emoji-mart/css/emoji-mart.css';
        document.head.appendChild(link);
    }
}

window.initEmojiPicker = function(inputSelector, buttonSelector, pickerContainerSelector) {
    const input = document.querySelector(inputSelector);
    const button = document.querySelector(buttonSelector);
    const pickerContainer = document.querySelector(pickerContainerSelector);
    let pickerVisible = false;
    let picker;

    if (!input || !button || !pickerContainer) return;

    button.addEventListener('click', function(e) {
        e.preventDefault();
        if (!pickerVisible) {
            picker = new Picker({
                set: 'apple',
                theme: 'auto',
                style: { position: 'absolute', zIndex: 9999 },
                onSelect: function(emoji) {
                    input.value += emoji.native;
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                }
            });
            if (picker && picker.render) {
                picker.render(pickerContainer);
            } else {
                pickerContainer.appendChild(picker);
            }
            pickerVisible = true;
        } else {
            pickerContainer.innerHTML = '';
            pickerVisible = false;
        }
    });

    document.addEventListener('click', function(e) {
        if (pickerVisible && !pickerContainer.contains(e.target) && e.target !== button) {
            pickerContainer.innerHTML = '';
            pickerVisible = false;
        }
    });
};
