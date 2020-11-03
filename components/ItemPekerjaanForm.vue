<template>
	<el-dialog
		v-loading="loading"
		:close-on-click-modal="false"
		:before-close="closeForm"
		:visible.sync="show"
		:title="!!formModel.id ? 'EDIT ITEM PEKERJAAN' : 'TAMBAH ITEM PEKERJAAN'"
	>
		<el-form label-position="left" label-width="200px">
			<el-form-item label="Urutan" :class="formError.urutan ? 'is-error' : ''">
				<el-input
					type="number"
					v-model="formModel.urutan"
					placeholder="Urutan"
				></el-input>
				<div class="el-form-item__error" v-if="formError.urutan">
					{{ formError.urutan.join(", ") }}
				</div>
			</el-form-item>

			<el-form-item label="Nama" :class="formError.nama ? 'is-error' : ''">
				<el-input v-model="formModel.nama" placeholder="Nama"></el-input>
				<div class="el-form-item__error" v-if="formError.nama">
					{{ formError.nama.join(", ") }}
				</div>
			</el-form-item>

			<!-- <el-form-item
				label="Keterangan"
				:class="formError.keterangan ? 'is-error' : ''"
			>
				<el-input
					v-model="formModel.keterangan"
					placeholder="Keterangan"
				></el-input>
				<div class="el-form-item__error" v-if="formError.keterangan">
					{{ formError.keterangan.join(", ") }}
				</div>
			</el-form-item> -->

			<!-- <el-form-item label="Sembunyikan di List">
				<el-checkbox v-model="formModel.hidden">Ya</el-checkbox>
			</el-form-item> -->
		</el-form>
		<div slot="footer">
			<el-button icon="el-icon-error" type="primary" plain @click="closeForm"
				>BATAL</el-button
			>
			<el-button
				icon="el-icon-success"
				type="primary"
				@click="() => (!!formModel.id ? update() : store())"
				>SIMPAN</el-button
			>
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
				.post("/api/jenisDetailPekerjaan", this.formModel)
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
				.put(`/api/jenisDetailPekerjaan/${this.formModel.id}`, this.formModel)
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
