import { createRouter, createWebHistory } from 'vue-router';
import { useUserStore } from '@/store/userStore'; // Importez le userStore depuis Pinia
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
    meta: { requiresAuth: true },
    children: [
      {
        path: 'profile',
        component: ProfilePage,
      },
      // Ajoutez d'autres routes enfants ici, par exemple:
      // {
      //   path: 'other-page',
      //   component: OtherPage,
      // },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Navigation guard
router.beforeEach((to, from, next) => {
  const userStore = useUserStore(); // Créez une instance de userStore
  
  // Check if the route requires authentication
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!userStore.isAuthenticated) { // Utilisez l'état d'authentification de userStore
      next('/login'); // Redirigez vers la page de connexion si non authentifié
    } else {
      next(); // Accédez à la route demandée si authentifié
    }
  } else {
    next(); // Accédez à la route demandée si elle ne nécessite pas d'authentification
  }
});

export default router;
