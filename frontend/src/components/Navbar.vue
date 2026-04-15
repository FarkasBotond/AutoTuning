<template>
  <nav class="navbar">
    <div class="nav-container">
      <router-link to="/" class="nav-brand">AutoTuning</router-link>

      <div class="nav-links">
        <template v-if="authStore.isAuthenticated">
          <span class="user-email">{{ authStore.user?.email }}</span>
          <button @click="handleLogout" class="nav-btn logout-btn">Logout</button>
        </template>
        <template v-else>
          <router-link to="/login" class="nav-btn login-btn">Login</router-link>
          <router-link to="/register" class="nav-btn register-btn">Register</router-link>
        </template>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { useAuthStore } from '@/stores/authStore'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

const handleLogout = () => {
  authStore.logout()
  router.push('/login')
}
</script>

<style scoped>
.navbar {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 1rem 0;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  position: sticky;
  top: 0;
  z-index: 100;
}

.nav-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.nav-brand {
  font-size: 1.5rem;
  font-weight: 700;
  color: white;
  text-decoration: none;
  transition: opacity 0.3s ease;
}

.nav-brand:hover {
  opacity: 0.8;
}

.nav-links {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.user-email {
  color: white;
  font-size: 0.9rem;
  margin-right: 0.5rem;
}

.nav-btn {
  padding: 0.5rem 1rem;
  border: 2px solid white;
  background: transparent;
  color: white;
  border-radius: 6px;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.3s ease;
  font-size: 0.9rem;
}

.nav-btn:hover {
  background: white;
  color: #667eea;
}

.login-btn,
.register-btn {
  border: 2px solid white;
}

.logout-btn {
  border: 2px solid white;
}

@media (max-width: 600px) {
  .nav-container {
    flex-direction: column;
    gap: 1rem;
  }

  .nav-links {
    width: 100%;
    justify-content: center;
  }

  .user-email {
    display: none;
  }
}
</style>
