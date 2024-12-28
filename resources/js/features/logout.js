export const initLogoutConfirm = () => {
  const confirmMessage = 'ログアウトしますか？';

  document.addEventListener('click', (e) => {
    const logoutButton = e.target.closest('.js-logout');
    if (!logoutButton) return;

    e.preventDefault();

    if (confirm(confirmMessage)) {
      window.location.href = logoutButton.href;
    }
  });
};
