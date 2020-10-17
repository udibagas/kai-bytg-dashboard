<template>
	<div class="login-container text-center" style="width: 320px">
		<div style="background-color: #eaeceb" class="py-3">
			<img :src="`${base}logo.jpeg`" alt />
		</div>
		<el-form class="text-center px-4">
			<h5 class="m-4">Silakan Login</h5>
			<el-form-item>
				<el-input
					prefix-icon="el-icon-user"
					placeholder="Email"
					v-model="loginForm.email"
				></el-input>
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
					style="width: 100%"
					@click="login"
					:disabled="!loginForm.email || !loginForm.password"
					>LOGIN</el-button
				>
			</el-form-item>
		</el-form>
		<small>&copy; {{ new Date().getFullYear() }} - BYTG</small>
	</div>
</template>

<script>
export default {
	layout: "login",
	data() {
		return {
			loginForm: {
				email: "",
				password: "",
				remember: true,
			},
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
					if (e.response.status == 422) {
						const message = e.response.data.errors.email
							? e.response.data.errors.email[0]
							: e.response.data.errors.password[0];
						this.$message({
							message: message,
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
	background-color: #2d005c;
	/* background-color: #0e5ca9; */
	height: 100vh;
	color: #fff;
}
</style>
