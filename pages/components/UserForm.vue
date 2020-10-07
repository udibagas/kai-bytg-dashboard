<template>
	<el-dialog
		v-loading="loading"
		:close-on-click-modal="false"
		:before-close="closeForm"
		:visible.sync="show"
		:title="!!formModel.id ? 'EDIT USER' : 'TAMBAH USER'"
	>
		<el-form label-position="left" label-width="200px">
			<el-form-item label="Nama" :class="formError.name ? 'is-error' : ''">
				<el-input v-model="formModel.name" placeholder="Nama"></el-input>
				<div class="el-form-item__error" v-if="formError.name">{{formError.name.join(', ')}}</div>
			</el-form-item>

			<el-form-item label="Email" :class="formError.email ? 'is-error' : ''">
				<el-input autocomplete="off" v-model="formModel.email" placeholder="Email"></el-input>
				<div class="el-form-item__error" v-if="formError.email">{{formError.email.join(', ')}}</div>
			</el-form-item>

			<el-form-item label="Password" :class="formError.password ? 'is-error' : ''">
				<el-input
					autocomplete="off"
					type="password"
					v-model="formModel.password"
					placeholder="Password"
				></el-input>
				<div class="el-form-item__error" v-if="formError.password">{{formError.password.join(', ')}}</div>
			</el-form-item>
			<el-form-item label="Konfirmasi Password" :class="formError.password ? 'is-error' : ''">
				<el-input
					autocomplete="off"
					type="password"
					v-model="formModel.password_confirmation"
					placeholder="Konfirmasi Password"
				></el-input>
				<div
					class="el-form-item__error"
					v-if="formError.password_confirmation"
				>{{formError.password_confirmation.join(', ')}}</div>
			</el-form-item>

			<!-- <el-form-item label="Role" :class="formError.role ? 'is-error' : ''">
				<el-select  v-model="formModel.role" filterable default-first-option>
					<el-option
						v-for="(label, value) in userRoleList"
						:key="value"
						:label="label"
						:value="parseInt(value)"
					></el-option>
				</el-select>
				<div class="el-form-item__error" v-if="formError.role">{{formError.role.join(', ')}}</div>
			</el-form-item>-->
		</el-form>
		<div slot="footer">
			<el-button icon="el-icon-error" type="primary" plain @click="closeForm">BATAL</el-button>
			<el-button
				icon="el-icon-success"
				type="primary"
				@click="() => !!formModel.id ? update() : store()"
			>SIMPAN</el-button>
		</div>
	</el-dialog>
</template>

<script>
export default {
	props: ["show", "data"],
	computed: {
		formModel() {
			return this.data;
		},
	},
	data() {
		return {
			loading: false,
			formError: {},
		};
	},
	methods: {
		store() {
			this.loading = true;
			this.$axios
				.post("/api/user", this.formModel)
				.then((r) => {
					this.closeForm();
					this.$emit("refresh-data");
					this.$message({
						message: r.data.message,
						type: "success",
						showClose: true,
					});
				})
				.catch((e) => {
					if (e.response.status == 422) {
						this.formError = e.response.data.errors;
					}

					if (e.response.status == 500) {
						this.formError = {};
					}

					this.$message({
						message: e.response.data.message,
						type: "error",
						showClose: true,
					});
				})
				.finally(() => {
					this.loading = false;
				});
		},
		update() {
			this.loading = true;
			this.$axios
				.put(`/api/user/${this.formModel.id}`, this.formModel)
				.then((r) => {
					this.closeForm();
					this.$emit("refresh-data");
					this.$message({
						message: r.data.message,
						type: "success",
						showClose: true,
					});
				})
				.catch((e) => {
					if (e.response.status == 422) {
						this.formError = e.response.data.errors;
					}

					if (e.response.status == 500) {
						this.formError = {};
					}

					this.$message({
						message: e.response.data.message,
						type: "error",
						showClose: true,
					});
				})
				.finally(() => {
					this.loading = false;
				});
		},
		closeForm() {
			this.formError = {};
			this.$emit("close-form");
		},
	},
};
</script>
