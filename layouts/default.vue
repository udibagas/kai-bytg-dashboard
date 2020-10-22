<template>
	<el-container>
		<el-aside width="auto">
			<el-menu
				router
				background-color="#2d005c"
				active-text-color="#fff"
				text-color="#bbb"
				:collapse="collapse"
				default-active="/"
			>
				<el-menu-item
					v-for="(menu, index) in menus"
					:key="index"
					:index="menu.path"
				>
					<i :class="menu.icon"></i>
					<span slot="title">{{ menu.label }}</span>
				</el-menu-item>
			</el-menu>
		</el-aside>
		<el-container>
			<el-header class="d-flex">
				<div>
					<i
						style="font-size: 18px"
						class="mr-2"
						:class="collapse ? 'el-icon-s-unfold' : 'el-icon-s-fold'"
						@click="collapse = !collapse"
					></i>
				</div>
				<div class="brand flex-grow-1">DASHBOARD SARANA BYTG</div>
				<el-popover placement="bottom" trigger="click">
					<div slot="reference">
						<el-avatar class="mt-2" icon="el-icon-user-solid"></el-avatar>
					</div>

					<div class="text-center p-4">
						<el-avatar :size="75" icon="el-icon-user-solid"></el-avatar>
						<h5 class="mt-2">{{ $auth.user.name.toUpperCase() }}</h5>
						<div class="mb-4">{{ $auth.user.email }}</div>
						<el-button
							icon="el-icon-edit"
							type="primary"
							size="mini"
							@click="showProfileDialog = true"
						>
							Edit Profil
						</el-button>
						<el-button
							icon="el-icon-d-arrow-right"
							size="mini"
							type="danger"
							@click="logout"
						>
							Keluar
						</el-button>
					</div>
				</el-popover>
				<div class="ml-2">{{ $auth.user.name }}</div>
			</el-header>
			<el-main>
				<Nuxt />
			</el-main>
		</el-container>

		<UserForm
			:show="showProfileDialog"
			:data="user"
			@close-form="showProfileDialog = false"
		/>
	</el-container>
</template>

<script>
export default {
	middleware: ["auth"],
	data() {
		return {
			menus: [],
			collapse: true,
			showProfileDialog: false,
			user: JSON.parse(JSON.stringify(this.$auth.user)),
		};
	},
	methods: {
		getMenu() {
			this.$axios.get("/api/menu").then((r) => (this.menus = r.data));
		},
		logout() {
			this.$auth.logout();
			this.$router.push("/login");
		},
	},
	created() {
		this.getMenu();
		this.$store.dispatch("getListJenisSarana");
		this.$store.dispatch("getListJenisPekerjaan");
		this.$store.dispatch("getListDipo");
		this.$store.dispatch("getListJalur");
		this.$store.dispatch("getListJenisDetailPekerjaan");
	},
};
</script>

<style lang="scss" scoped>
.el-header {
	line-height: 60px;
	border-bottom: 1px solid #ddd;
}

.el-menu {
	height: 100vh;
}

.el-menu-item.is-active {
	background-color: rgb(25, 58, 137);
}
</style>
