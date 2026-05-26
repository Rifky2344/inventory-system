function resolvePasswordInput(toggleButton) {
	const targetId = toggleButton.getAttribute('data-target');
	if (targetId) {
		const byId = document.getElementById(targetId);
		if (byId instanceof HTMLInputElement) return byId;
	}

	// Fallback: find the nearest input in the same wrapper.
	const wrapper = toggleButton.closest('.relative');
	if (!wrapper) return null;

	const candidate = wrapper.querySelector('input');
	return candidate instanceof HTMLInputElement ? candidate : null;
}

function syncToggleLabel(toggleButton, input) {
	const showText = toggleButton.getAttribute('data-show-text') ?? 'Show';
	const hideText = toggleButton.getAttribute('data-hide-text') ?? 'Hide';
	const isHidden = input.type === 'password';
	const label = isHidden ? showText : hideText;

	// Keep button contents intact (so we can render icons).
	const labelEl = toggleButton.querySelector('[data-toggle-label]');
	if (labelEl) labelEl.textContent = label;

	toggleButton.setAttribute('aria-label', label);
	toggleButton.setAttribute('title', label);
	toggleButton.setAttribute('aria-pressed', isHidden ? 'false' : 'true');

	const showIcon = toggleButton.querySelector('[data-icon-show]');
	const hideIcon = toggleButton.querySelector('[data-icon-hide]');
	if (showIcon) showIcon.classList.toggle('hidden', !isHidden);
	if (hideIcon) hideIcon.classList.toggle('hidden', isHidden);
}

document.addEventListener('click', (event) => {
	const toggleButton = event.target instanceof Element
		? event.target.closest('[data-toggle-password]')
		: null;

	if (!toggleButton) return;

	const input = resolvePasswordInput(toggleButton);
	if (!input) return;

	const isHidden = input.type === 'password';
	input.type = isHidden ? 'text' : 'password';
	input.focus();

	syncToggleLabel(toggleButton, input);
});

document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('[data-toggle-password]').forEach((el) => {
		if (!(el instanceof HTMLElement)) return;
		const input = resolvePasswordInput(el);
		if (!input) return;
		syncToggleLabel(el, input);
	});
});
