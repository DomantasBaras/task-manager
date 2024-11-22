<template>
  <div class="min-h-screen flex flex-col bg-gray-100">
    <!-- Navigation -->
    <nav style="background-color: rgb(80, 10, 172);" class="shadow-md">
      <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <!-- Logo -->
        <div class="flex items-center">
          <Navlink href="/" class="text-lg font-bold text-gray-800">
            Wira Dashboard
          </Navlink>
        </div>

        <!-- Desktop Menu -->
        <div class="hidden md:flex space-x-6">
          <Navlink href="/dashboard" class="text-black-600 hover:text-slate-100">Dashboard</Navlink>
          <Navlink href="/profile" class="text-black-600 hover:text-slate-100">Profile</Navlink>
          
          <!-- Conditionally render Log Out or Log In based on user authentication -->
          <template v-if="$page.props.auth.user">
            <!-- User is logged in -->
            <NavLink 
              class="text-black-600 hover:text-slate-100"
              :href="route('logout')" 
              method="post"
            >
              Log Out
            </NavLink>
          </template>
          <template v-else>
            <!-- User is logged out -->
            <NavLink 
              class="text-black-600 hover:text-slate-100"
              :href="route('login')"
            >
              Log In
            </NavLink>
          </template>
        </div>

        <!-- Mobile Menu Button -->
        <button @click="toggleMenu" class="md:hidden text-black-600 focus:outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path v-if="!menuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>

      <!-- Mobile Menu -->
      <div v-if="menuOpen" class="md:hidden bg-white shadow-md">
        <Navlink href="/dashboard" class="block px-4 py-2 text-black-600 hover:text-slate-100">Dashboard</Navlink>
        <Navlink href="/profile" class="block px-4 py-2 text-black-600 hover:text-slate-100">Profile</Navlink>

        <!-- Conditionally render Log Out or Log In in mobile menu -->
        <template v-if="$page.props.auth.user">
          <!-- User is logged in -->
          <Navlink 
            :href="route('logout')" 
            method="post" 
            class="block px-4 py-2 text-black-600 hover:text-slate-100"
          >
            Log Out
          </Navlink>
        </template>
        <template v-else>
          <!-- User is logged out -->
          <Navlink 
            :href="route('login')" 
            class="block px-4 py-2 text-black-600 hover:text-slate-100"
          >
            Log In
          </Navlink>
        </template>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 container mx-auto px-4 py-6">
      <slot />
    </main>

    <!-- Footer -->
    <footer style="background-color: rgb(80, 10, 172);" class="shadow-md text-center py-4 text-black-600">
      &copy; 2024 Wira Dashboard. All rights reserved.
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import NavLink from '@/Components/NavLink.vue';

const menuOpen = ref(false);

// Toggle the mobile menu
const toggleMenu = () => {
  menuOpen.value = !menuOpen.value;
};
</script>

