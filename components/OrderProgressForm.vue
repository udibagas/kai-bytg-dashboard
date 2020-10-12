<template>
	<el-dialog
		v-loading="loading"
		:close-on-click-modal="false"
		:before-close="closeForm"
		:visible.sync="show"
		title="UPDATE DETAIL ORDER"
	>
		<el-form label-position="left" label-width="200px">
			<el-form-item
				label="Pekerjaan"
				:class="formError.jenis_detail_pekerjaan_id ? 'is-error' : ''"
			>
				<el-select
					disabled
					v-model="formModel.jenis_detail_pekerjaan_id"
					style="width: 100%"
					placeholder="Pilih Pekerjaan"
					filterable
					default-first-option
					allow-create
				>
					<el-option
						v-for="p in listJenisDetailPekerjaan"
						:key="p.id"
						:value="p.id"
						:label="p.nama"
					></el-option>
				</el-select>
				<div
					class="el-form-item__error"
					v-if="formError.jenis_detail_pekerjaan_id"
				>
					{{ formError.jenis_detail_pekerjaan_id.join(", ") }}
				</div>
			</el-form-item>

			<el-form-item
				label="Prosentase Pekerjaan"
				:class="formError.prosentase_pekerjaan ? 'is-error' : ''"
			>
				<div class="d-flex">
					<el-slider
						class="flex-grow-1 mr-3"
						v-model="formModel.prosentase_pekerjaan"
						:step="5"
					></el-slider>
					<div>{{ formModel.prosentase_pekerjaan }}%</div>
				</div>
				<div class="el-form-item__error" v-if="formError.prosentase_pekerjaan">
					{{ formError.prosentase_pekerjaan.join(", ") }}
				</div>
			</el-form-item>

			<el-form-item label="Keterangan">
				<el-input
					type="textarea"
					placeholder="Keterangan"
					rows="5"
					v-model="formModel.keterangan"
				></el-input>
			</el-form-item>
		</el-form>
		<div slot="footer">
			<el-button icon="el-icon-error" type="primary" plain @click="closeForm"
				>BATAL</el-button
			>
			<el-button icon="el-icon-success" type="primary" @click="update"
				>SIMPAN</el-button
			>
		</div>
	</el-dialog>
</template>

<script>
import { mapState } from "vuex";

export default {
	props: ["show", "data"],
	computed: {
		formModel() {
			return this.data;
		},
		...mapState(["listJenisDetailPekerjaan"]),
	},
	data() {
		return {
			loading: false,
			formError: {},
		};
	},
	methods: {
		update() {
			this.loading = true;
			this.$axios
				.put(`/api/orderDetail/${this.data.id}`, this.formModel)
				.then((r) => {
					this.closeForm();
					this.$emit("reload-data");
					this.$message({
						message: r.data.message,
						type: "success",
					});

					this.$store.dispatch("getListJenisDetailPekerjaan");
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
	created() {
		this.$store.dispatch("getListJenisDetailPekerjaan");
	},
};
</script>
