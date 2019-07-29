/* eslint-disable no-new */

import Vue from 'vue';
import svg4everybody from 'svg4everybody';

import './bootstrap';
import lang from './i18n';

import EButton from './components/common/button';
import ELabel from './components/common/label';
import ETable from './components/common/table';
import ForgotPasswordForm from './components/accounts/forgot-password-form';
import Icon from './components/common/icon';
import IconText from './components/common/icon-text';
import LoginForm from './components/accounts/login-form';
import PasswordResetForm from './components/accounts/password-reset-form';
import RegisterForm from './components/accounts/register-form';
import ResendVerifyCodeForm from './components/accounts/resend-verify-code-form';

Vue.filter('trans', (...args) => lang.get(...args));

// Global
Vue.component('EButton', EButton);
Vue.component('ELabel', ELabel);
Vue.component('ETable', ETable);
Vue.component('Icon', Icon);
Vue.component('IconText', IconText);

new Vue({
	el: '#app',

	// Local
	components: {
		// App
		ForgotPasswordForm,
		LoginForm,
		PasswordResetForm,
		RegisterForm,
		ResendVerifyCodeForm,

		// Styleguide
		// ExampleStyleguideOnlyComponent,
	},

	mounted() {
		svg4everybody();
	},
});
