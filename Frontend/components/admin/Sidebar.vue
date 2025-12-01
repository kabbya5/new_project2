<template>
  <div v-if="sidebar.isOpen" id="admin-sidebar" class="h-full side-menu">

    <aside>

      <!-- Logo -->
      <NuxtLink to="/admin/dashboard" class="flex justify-center items-center border-b py-3">
        <NuxtImg src="/logo.png" class="h-6" />
      </NuxtLink>

      <nav class="px-3 py-2 space-y-1">

        <!-- Dashboard -->
        <NuxtLink to="/admin/dashboard" class="sidebar-link">
          <i class="fas fa-home"></i>
          <span>Dashboard</span>
        </NuxtLink>

        <!-- Dynamic Menu -->
        <div v-for="(group, index) in navLinks" :key="index">

          <!-- Single Link -->
          <NuxtLink
            v-if="group.single"
            :to="group.url"
            class="single-link"
            :class="{ active: isActive(group.url) }">
            {{ group.title }}
          </NuxtLink>

          <!-- Group Menu -->
          <div v-else class="menu-group">

            <!-- Parent -->
            <div class="menu-title" @click="toggleGroup(index)">
              <div class="flex items-center gap-2">
                <i :class="group.icon"></i>
                <span>{{ group.title }}</span>
              </div>
              <i class="fa-solid fa-chevron-down arrow" :class="{ rotate: group.open }"></i>
            </div>

            <!-- Children -->
            <transition name="slide">
              <ul v-show="group.open" class="submenu">

                <li v-for="child in group.children" :key="child.url">
                  <NuxtLink
                    :to="child.url"
                    class="submenu-link"
                    :class="{ active: isActive(child.url) }">
                    <i :class="child.icon"></i>
                    {{ child.title }}
                  </NuxtLink>
                </li>

              </ul>
            </transition>

          </div>

        </div>

        <!-- Logout -->
        <button @click="logout" class="single-link logout">
          <i class="fa-solid fa-right-from-bracket"></i> Logout
        </button>

      </nav>

    </aside>

  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/auth'
import { useRoute, useRouter } from 'vue-router'

const sidebar = useSidebarTogglerStore()
const authStore = useAuthStore()
const { handleLogout } = authStore

const router = useRouter()
const route = useRoute()

/* ------------------- NAV MENU ------------------- */

const navLinks = ref([
  {
    title: "Admin Setting",
    icon: "fa-solid fa-gear",
    open: false,
    children: [
      { title: "Slider", icon: "fa-regular fa-images", url: "/admin/slider" },
      { title: "Moving Text", icon: "fa-solid fa-text-width", url: "/admin/text" }
    ]
  },
  {
    title: "Payment Setting",
    icon: "fa-solid fa-wallet",
    open: false,
    children: [
      { title: "Transaction Number", icon: "fa-solid fa-ad", url: "/admin/transaction/deposit-number" },
      { title: "Deposit", icon: "fa-solid fa-comments-dollar", url: "/admin/transaction/deposit" },
      { title: "Withdraw", icon: "fa-solid fa-coins", url: "/admin/transaction/withdraw" },
      { title: "Transaction History", icon: "fa-solid fa-receipt", url: "/admin/transaction" }
    ]
  },
  {
    title: "Game Setting",
    icon: "fa-solid fa-gamepad",
    open: false,
    children: [
      { title: "Category", icon: "fa-regular fa-calendar-days", url: "/admin/category" },
      { title: "Provider", icon: "fa-regular fa-bookmark", url: "/admin/provider" },
      { title: "Game", icon: "fa-solid fa-trophy", url: "/admin/game" },
      { title: "Game Records", icon: "fa-solid fa-receipt", url: "/admin/transaction/record" },
      { title: "Game Balance", icon: "fa-solid fa-sack-dollar", url: "/admin/game-balance" }
    ]
  },
  {
    title: "User Setting",
    icon: "fa-solid fa-users",
    open: false,
    children: [
      { title: "Users", icon: "fa-solid fa-user-secret", url: "/admin/user" },
      { title: "VIP Level", icon: "fa-solid fa-tag", url: "/admin/label" }
    ]
  },
  {
    title: "Agent & Affiliate",
    icon: "fa-solid fa-user-group",
    open: false,
    children: [
      { title: "Agent", icon: "fa-solid fa-user-group", url: "/admin/agents" },
      { title: "Affiliate", icon: "fa-solid fa-users-line", url: "/admin/affiliate" },

    ]
  },
  { title: "VIP", url: "/admin/label", single: true },
  { title: "Promotion Page", url: "/admin/promotion", single: true }
])

/* ------------------- UTILS ------------------- */

const normalize = (path: string) => path.replace(/\/$/, '')

const isActive = (url: string) => {
  const current = normalize(route.path)
  const target = normalize(url)

  // only exact match for parent route
  if (target === '/admin/transaction') {
    return current === target
  }

  // all other routes (allow deep match)
  return current === target || current.startsWith(target + '/')
}

/* ------------------- ACCORDION ------------------- */

const toggleGroup = (index: number) => {
  navLinks.value.forEach((group, i) => {
    if (!group.single) {
      group.open = i === index ? !group.open : false
    }
  })
}

/* ----------- AUTO OPEN GROUP BY ROUTE ------------ */

watchEffect(() => {
  const currentPath = normalize(route.path)

  navLinks.value.forEach(group => {
    if (!group.children) return

    group.open = group.children.some(child => {
      const childPath = normalize(child.url)

      if (childPath === '/admin/transaction') {
        return currentPath === childPath
      }

      return (
        currentPath === childPath ||
        currentPath.startsWith(childPath + '/')
      )
    })
  })
})

/* ------------------- LOGOUT ------------------- */

const logout = async () => {
  await handleLogout()
  router.push('/')
}

/* ---------------- RESPONSIVE ------------------- */

const updateWindowSize = () => {
  if (window.innerWidth <= 1024) sidebar.close()
  else sidebar.open()
}

onMounted(() => {
  updateWindowSize()
  window.addEventListener('resize', updateWindowSize)
})

onUnmounted(() => {
  window.removeEventListener('resize', updateWindowSize)
})
</script>

<style scoped>
#admin-sidebar {
  width: 230px;
  min-width: 230px;
}

.sidebar-link, .single-link {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 7px;
  border-radius: 6px;
  text-decoration: none;
  color: white;
}

.sidebar-link:hover, .single-link:hover {
  background: rgba(0,0,0,0.3);
}

.menu-title {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 7px;
  cursor: pointer;
  border-radius: 6px;
  background: rgba(0,0,0,0.2);
}

.menu-title:hover {
  background: rgba(0,0,0,0.4);
}

.submenu {
  list-style: none;
  padding-left: 15px;
}

.submenu-link {
  display: flex;
  gap: 8px;
  padding: 5px;
  color: white;
}

.submenu-link.active,
.single-link.active {
  color: gold;
  font-weight: bold;
}

.arrow {
  transition: .3s;
}

.rotate {
  transform: rotate(180deg);
}

/* Slide animation */
.slide-enter-active, .slide-leave-active {
  transition: all 0.25s ease;
}
.slide-enter-from, .slide-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}

.logout {
  margin-top: 8px;
  padding: 7px 30px;
  background: #b02a2a;
}
</style>
