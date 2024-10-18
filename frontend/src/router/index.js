import { createRouter, createWebHistory } from 'vue-router'; // Vue 3 router API

// Import components
import Register from '@/components/Register.vue';
import Login from '@/components/Login.vue';
import Home from '@/components/home.vue';
import TodoList from '@/components/TodoList.vue';
import Users from '@/components/Users.vue';
// Define your routes
const routes = [
  {
    path: '/',
    name: 'Login',
    component: Login
  },
  {
    path: '/register',
    name: 'Register',
    component: Register
  },
  {
    path: '/home',
    name: 'Home',
    component: Home,
    meta: { requiresAuth: true },
    children: [
      {
        path: 'todo-list',
        name:'todo-list',
        component: TodoList,
      },
      {
        path: 'users',
        name:'users',
        component: Users,
      },
    ],
  }
];

// Create the router instance
const router = createRouter({
  history: createWebHistory(process.env.BASE_URL), 
  routes
});


function isAuthenticated() {
  // const token = document.cookie.split('; ').find(row => row.startsWith('authToken='));
  const token = localStorage.getItem('token')
  if(!token){
    return false
  }
  return token;
   
}

router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !isAuthenticated()) {
    next({ name: 'Login' });
  } else {
    next();
  }
});

export default router;
