<template>
	<el-dialog
		v-loading="loading"
		:close-on-click-modal="false"
		:before-close="closeForm"
		:visible.sync="show"
		:title="!!formModel.id ? 'EDIT SARANA' : 'TAMBAH SARANA'"
	>
		<el-form label-position="left" label-width="200px">
			<el-form-item label="Nomor" :class="formError.nomor ? 'is-error' : ''">
				<el-input v-model="formModel.nomor" placeholder="Nomor"></el-input>
				<div class="el-form-item__error" v-if="formError.nomor">
					{{ formError.nomor.join(", ") }}
				</div>
			</el-form-item>

			<el-form-item
				label="Nomor Lama"
				:class="formError.nomor_lama ? 'is-error' : ''"
			>
				<el-input
					v-model="formModel.nomor_lama"
					placeholder="Nomor Lama"
				></el-input>
				<div class="el-form-item__error" v-if="formError.nomor_lama">
					{{ formError.nomor_lama.join(", ") }}
				</div>
			</el-form-item>

			<el-form-item
				label="Jenis Sarana"
				:class="formError.jenis_sarana_id ? 'is-error' : ''"
			>
				<el-select
					v-model="formModel.jenis_sarana_id"
					placeholder="Jenis Sarana"
					filterable
					default-first-option
					style="width: 100%"
				>
					<el-option
						v-for="js in listJenisSarana"
						:key="js.id"
						:label="js.nama"
						:value="js.id"
					></el-option>
				</el-select>
				<div class="el-form-item__error" v-if="formError.jenis_sarana_id">
					{{ formError.jenis_sarana_id.join(", ") }}
				</div>
			</el-form-item>

			<el-form-item
				label="Jenis Bogie"
				:class="formError.bogie_id ? 'is-error' : ''"
			>
				<el-select
					v-model="formModel.bogie_id"
					placeholder="Jenis Bogie"
					style="width: 100%"
					filterable
					default-first-option
				>
					<el-option
						v-for="js in listBogie"
						:key="js.id"
						:label="`${js.kode} - ${js.nama}`"
						:value="js.id"
					></el-option>
				</el-select>
				<div class="el-form-item__error" v-if="formError.bogie_id">
					{{ formError.bogie_id.join(", ") }}
				</div>
			</el-form-item>

			<el-form-item label="Dipo" :class="formError.dipo_id ? 'is-error' : ''">
				<el-select
					v-model="formModel.dipo_id"
					placeholder="Dipo"
					filterable
					default-first-option
					style="width: 100%"
				>
					<el-option
						v-for="dipo in listDipo"
						:key="dipo.id"
						:label="dipo.nama"
						:value="dipo.id"
					></el-option>
				</el-select>
				<div class="el-form-item__error" v-if="formError.dipo_id">
					{{ formError.dipo_id.join(", ") }}
				</div>
			</el-form-item>

			<el-form-item
				label="Keterangan"
				:class="formError.keterangan ? 'is-error' : ''"
			>
				<el-input
					type="textarea"
					:rows="3"
					v-model="formModel.keterangan"
					placeholder="Keterangan"
				></el-input>
				<div class="el-form-item__error" v-if="formError.keterangan">
					{{ formError.keterangan.join(", ") }}
				</div>
			</el-form-item>
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
import { mapState } from "vuex";

export default {
	props: ["show", "data"],
	computed: {
		formModel() {
			return this.data;
		},
		...mapState(["listJenisSarana", "listDipo", "listBogie"]),
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
				.post("/api/sarana", this.formModel)
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
				.put(`/api/sarana/${this.formModel.id}`, this.formModel)
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
