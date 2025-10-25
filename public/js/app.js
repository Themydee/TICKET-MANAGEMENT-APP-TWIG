// ===== AUTHENTICATION FUNCTIONS =====

// Check if user is authenticated
function isAuthenticated() {
  return !!localStorage.getItem('ticketapp_session');
}

// Get current user
function getCurrentUser() {
  const userData = localStorage.getItem('ticketapp_user');
  return userData ? JSON.parse(userData) : null;
}

// Login function
function login(email, password) {
  const users = JSON.parse(localStorage.getItem('ticketapp_users') || '[]');
  const user = users.find(u => u.email === email && u.password === password);
  
  if (user) {
    const token = 'token_' + Date.now();
    localStorage.setItem('ticketapp_session', token);
    
    const userData = { email: user.email, username: user.username };
    localStorage.setItem('ticketapp_user', JSON.stringify(userData));
    
    return { success: true };
  }
  
  return { success: false, error: 'Invalid email or password' };
}

// Signup function
function signup(email, username, password) {
  const users = JSON.parse(localStorage.getItem('ticketapp_users') || '[]');
  
  if (users.find(u => u.email === email)) {
    return { success: false, error: 'Email already exists' };
  }

  const newUser = { email, username, password };
  users.push(newUser);
  localStorage.setItem('ticketapp_users', JSON.stringify(users));

  const token = 'token_' + Date.now();
  localStorage.setItem('ticketapp_session', token);
  
  const userData = { email, username };
  localStorage.setItem('ticketapp_user', JSON.stringify(userData));
  
  return { success: true };
}

// Logout function
function logout() {
  localStorage.removeItem('ticketapp_session');
  localStorage.removeItem('ticketapp_user');
  window.location.href = '/index.php';
}

// ===== TOAST NOTIFICATION =====

function showToast(message, type = 'success') {
  const existingToast = document.querySelector('.toast');
  if (existingToast) {
    existingToast.remove();
  }

  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.innerHTML = `
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="${type === 'success' ? '#10b981' : '#ef4444'}" stroke-width="2">
      ${type === 'success' 
        ? '<polyline points="20 6 9 17 4 12"></polyline>' 
        : '<circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line>'}
    </svg>
    <span>${message}</span>
    <button onclick="this.parentElement.remove()" style="background: none; border: none; cursor: pointer; padding: 0">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="18" y1="6" x2="6" y2="18"></line>
        <line x1="6" y1="6" x2="18" y2="18"></line>
      </svg>
    </button>
  `;
  
  document.body.appendChild(toast);
  
  setTimeout(() => {
    toast.remove();
  }, 3000);
}

// ===== UTILITY FUNCTIONS =====

// Format date
function formatDate(dateString) {
  return new Date(dateString).toLocaleDateString();
}

// Get status class
function getStatusClass(status) {
  const classes = {
    open: 'status-open',
    in_progress: 'status-in_progress',
    closed: 'status-closed'
  };
  return classes[status] || 'status-open';
}

// Format status text
function formatStatus(status) {
  const labels = {
    open: 'Open',
    in_progress: 'In Progress',
    closed: 'Closed'
  };
  return labels[status] || status;
}

// Get priority color
function getPriorityColor(priority) {
  const colors = {
    low: '#10b981',
    medium: '#f59e0b',
    high: '#ef4444'
  };
  return colors[priority] || '#6b7280';
}

// Protect page (redirect if not authenticated)
function protectPage() {
  if (!isAuthenticated()) {
    window.location.href = '/index.php?page=login';
  }
}

// Check auth and redirect
function checkAuth() {
  const urlParams = new URLSearchParams(window.location.search);
  const page = urlParams.get('page');
  
  if (isAuthenticated() && (page === 'login' || page === 'signup' || !page)) {
    window.location.href = '/index.php?page=dashboard';
  }
}