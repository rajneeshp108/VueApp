import Vue from 'vue';
import VueRouter from 'vue-router';

import Dashboard from '../components/Dashboard.vue';
import Profile from '../components/Profile.vue';
import Users from '../components/Users.vue';

Vue.use(VueRouter);

const router = new VueRouter({
	mode : 'history',
	routes : [

		{ 	path: '/dashboard',
			name: 'Dashboard',  
			component: Dashboard
		}, { 	
			path: '/profile', 
			name: 'Profile', 
			component: Profile
		}, { 	
			path: '/users', 
			name: 'Users', 
			component: Users
		}
	]
})

export default router;