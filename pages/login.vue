<template>
	<div class="login-container pl-4 pr-4 text-center" style="width:320px;">
		<img :src="`${base}logo.png`" alt />
		<el-form class="text-center">
			<h5 class="m-4">Silakan Login</h5>
			<el-form-item>
				<el-input prefix-icon="el-icon-user" placeholder="Email" v-model="loginForm.email"></el-input>
			</el-form-item>
			<el-form-item>
				<el-input
					prefix-icon="el-icon-lock"
					type="password"
					placeholder="Password"
					v-model="loginForm.password"
				></el-input>
			</el-form-item>
			<el-form-item>
				<el-button
					type="primary"
					style="width:100%"
					@click="login"
					:disabled="!loginForm.email || !loginForm.password"
				>LOGIN</el-button>
			</el-form-item>
		</el-form>
		<small>&copy; {{(new Date()).getFullYear()}} - BYTG</small>
	</div>
</template>

<script>
export default {
	layout: "login",
	data() {
		return {
			loginForm: {},
			base: process.env.ROUTER_BASE || "/",
		};
	},
	created() {
		this.$axios.$get("/sanctum/csrf-cookie");
	},
	methods: {
		login() {
			this.$auth
				.loginWith("local", {
					data: this.loginForm,
				})
				.then((r) => {
					this.$router.push(this.$route.query.redirect || "/");
				})
				.catch((e) => {
					if (e.response.status == 422 || e.response.status == 429) {
						this.$message({
							message: e.response.data.errors.password[0],
							type: "error",
						});
					}
				});
		},
	},
};
</script>

<style lang="css" scoped>
.login-container {
	background-color: #0e5ca9;
	height: 100vh;
	color: #fff;
}
</style>
