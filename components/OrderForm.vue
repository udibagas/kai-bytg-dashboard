<template>
	<el-dialog
		v-loading="loading"
		:close-on-click-modal="false"
		:before-close="closeForm"
		:visible.sync="show"
		:title="!!formModel.id ? 'EDIT ORDER' : 'INPUT ORDER'"
	>
		<el-form label-position="left" label-width="200px">
			<el-form-item
				label="Nomor Order"
				:class="formError.nomor ? 'is-error' : ''"
			>
				<el-input
					v-model="formModel.nomor"
					placeholder="Nomor Order"
				></el-input>
				<div class="el-form-item__error" v-if="formError.nomor">
					{{ formError.nomor.join(", ") }}
				</div>
			</el-form-item>

			<el-form-item
				label="Tanggal Masuk"
				:class="formError.tanggal_masuk ? 'is-error' : ''"
			>
				<el-date-picker
					v-model="formModel.tanggal_masuk"
					placeholder="Tanggal Masuk"
					format="dd-MMM-yyyy"
					value-format="yyyy-MM-dd"
					style="width: 100%"
				></el-date-picker>
				<div class="el-form-item__error" v-if="formError.tanggal_masuk">
					{{ formError.tanggal_masuk.join(", ") }}
				</div>
			</el-form-item>

			<el-form-item
				label="Nomor Sarana"
				:class="formError.sarana_id ? 'is-error' : ''"
			>
				<el-select
					v-model="formModel.sarana_id"
					style="width: 100%"
					@change="updateForm"
					placeholder="Nomor Sarana"
					filterable
					default-first-option
					allow-create
				>
					<el-option
						v-for="sarana in listSarana"
						:key="sarana.id"
						:label="sarana.nomor"
						:value="sarana.id"
					></el-option>
				</el-select>
				<div class="el-form-item__error" v-if="formError.sarana_id">
					{{ formError.sarana_id.join(", ") }}
				</div>
			</el-form-item>

			<el-form-item
				label="Jenis Sarana"
				:class="formError.jenis_sarana_id ? 'is-error' : ''"
			>
				<el-select
					v-model="formModel.jenis_sarana_id"
					placeholder="Jenis Sarana"
					style="width: 100%"
					filterable
					default-first-option
				>
					<el-option
						v-for="js in listJenisSarana"
						:key="js.id"
						:label="`${js.kode} - ${js.nama}`"
						:value="js.id"
					></el-option>
				</el-select>
				<div class="el-form-item__error" v-if="formError.jenis_sarana_id">
					{{ formError.jenis_sarana_id.join(", ") }}
				</div>
			</el-form-item>

			<el-form-item label="Dipo" :class="formError.dipo_id ? 'is-error' : ''">
				<el-select
					v-model="formModel.dipo_id"
					placeholder="Dipo"
					style="width: 100%"
					filterable
					default-first-option
				>
					<el-option
						v-for="dipo in listDipo"
						:key="dipo.id"
						:label="`${dipo.kode} - ${dipo.nama}`"
						:value="dipo.id"
					></el-option>
				</el-select>
				<div class="el-form-item__error" v-if="formError.dipo_id">
					{{ formError.dipo_id.join(", ") }}
				</div>
			</el-form-item>

			<el-form-item
				label="Jenis Pekerjaan"
				:class="formError.jenis_pekerjaan_id ? 'is-error' : ''"
			>
				<el-select
					v-model="formModel.jenis_pekerjaan_id"
					placeholder="Jenis Pekerjaan"
					filterable
					default-first-option
					style="width: 100%"
				>
					<el-option
						v-for="jp in listJenisPekerjaan"
						:key="jp.id"
						:label="`${jp.kode} - ${jp.nama}`"
						:value="jp.id"
					></el-option>
				</el-select>
				<div class="el-form-item__error" v-if="formError.jenis_pekerjaan_id">
					{{ formError.jenis_pekerjaan_id.join(", ") }}
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
		...mapState([
			"listJenisSarana",
			"listDipo",
			"listJalur",
			"listJenisPekerjaan",
			"listSarana",
		]),
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
				.post("/api/order", this.formModel)
				.then((r) => {
					this.closeForm();
					this.$emit("refresh-data");
					this.$message({
						message: r.data.message,
						type: "success",
					});

					this.$store.dispatch("getListDipo");
					this.$store.dispatch("getListSarana");
					this.$store.dispatch("getListJalur");
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
		update() {
			this.loading = true;
			this.$axios
				.put(`/api/order/${this.formModel.id}`, this.formModel)
				.then((r) => {
					this.closeForm();
					this.$emit("refresh-data");
					this.$message({
						message: r.data.message,
						type: "success",
					});

					this.$store.dispatch("getListDipo");
					this.$store.dispatch("getListSarana");
					this.$store.dispatch("getListJalur");
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
		updateForm() {
			const sarana = this.listSarana.find(
				(s) => s.id == this.formModel.sarana_id
			);

			if (sarana) {
				this.formModel.nomor_lama = sarana.nomor_lama;
				this.formModel.jenis_sarana_id = sarana.jenis_sarana_id;
				this.formModel.dipo_id = sarana.dipo_id;
			}
		},
		closeForm() {
			this.formError = {};
			this.$emit("close-form");
		},
	},
	created() {
		this.$store.dispatch("getListSarana");
	},
};
</script>
