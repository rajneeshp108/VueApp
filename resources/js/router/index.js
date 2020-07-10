import Vue from 'vue';
import VueRouter from 'vue-router';

import Dashboard from '../components/Dashboard.vue';
import Profile from '../components/Profile.vue';
import Users from '../components/Users.vue';
import NotFound from '../components/NotFound.vue';
import Random404 from '../components/NotFound.vue';

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
		}, { 	
			path: '/404', 
			name: 'NotFound', 
			component: NotFound
		}, { 	
			path: '*', 
			name: 'Random404', 
			component: Random404
		}
	]
})

export default router;