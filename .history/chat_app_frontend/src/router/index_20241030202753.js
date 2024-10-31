import { createRouter, createWebHistory } from 'vue-router';
import { useUserStore } from '@/store/userStore';
import LoginPage from '@/views/LoginPage.vue';
import RegisterPage from '@/views/RegisterPage.vue';
import DashboardPage from '@/views/DashboardPage.vue';
import ProfilePage from '@/views/ProfilePage.vue';

const routes = [
  { path: '/login', component: LoginPage },
  { path: '/register', component: RegisterPage },
  {
    path: '/',
    component: DashboardPage,
    meta: { requiresAuth: true }, // Dashboard requiert une authentification
    children: [
      {
        path: 'profile',
        component: ProfilePage,
      },
      // Ajoutez d'autres routes enfants ici
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Navigation guard pour la vérification de l'authentification
router.beforeEach((to, from, next) => {
  const userStore = useUserStore(); // Accès au store utilisateur
  
  // Vérification si la route nécessite une authentification
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!userStore.isAuthenticated) { // Redirection si non authentifié
      next('/login');
    } else {
      next(); // Accéder à la route si authentifié
    }
  } else {
    next(); // Accéder à la route si elle ne nécessite pas d'authentification
  }
});

export default router;
