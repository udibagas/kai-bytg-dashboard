<template>
	<div>
		<el-card header="Update Progress" class="mb-3">
			<el-form label-position="left" label-width="200px">
				<el-form-item
					label="Pekerjaan"
					:class="formError.jenis_detail_pekerjaan_id ? 'is-error' : ''"
				>
					<el-select
						v-model="formModel.jenis_detail_pekerjaan_id"
						@change="getPercentage"
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
						>
							<span style="float: left">{{ p.nama }}</span>
							<i
								class="el-icon-circle-close"
								style="float: right; line-height: 30px"
								@click.prevent="removeJenisDetailPekerjaan(p.id)"
							></i>
						</el-option>
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
					<div
						class="el-form-item__error"
						v-if="formError.prosentase_pekerjaan"
					>
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

				<el-form-item
					label="Status Order"
					:class="formError.status ? 'is-error' : ''"
				>
					<el-select
						placeholder="Status Order"
						v-model="formModel.status"
						style="width: 100%"
					>
						<el-option :value="0" label="Terdaftar"></el-option>
						<el-option :value="10" label="Proses"></el-option>
						<el-option :value="20" label="Selesai"></el-option>
					</el-select>
					<div class="el-form-item__error" v-if="formError.status">
						{{ formError.status.join(", ") }}
					</div>
				</el-form-item>

				<el-form-item
					v-show="formModel.status == 20"
					label="Tanggal Keluar"
					:class="formError.tanggal_keluar ? 'is-error' : ''"
				>
					<el-date-picker
						type="date"
						format="dd-MMM-yyyy"
						value-format="yyyy-MM-dd"
						v-model="formModel.tanggal_keluar"
						placeholder="Tanggal Keluar"
						style="width: 100%"
						clearable
					></el-date-picker>
					<div class="el-form-item__error" v-if="formError.tanggal_keluar">
						{{ formError.tanggal_keluar.join(", ") }}
					</div>
				</el-form-item>

				<el-form-item>
					<el-button type="primary" plain @click="reset">RESET</el-button>
					<el-button type="primary" @click="submit">SIMPAN</el-button>
				</el-form-item>
			</el-form>
		</el-card>
		<el-card header="Riwayat Order">
			<!-- <el-timeline>
				<el-timeline-item
					v-for="p in order.order_progress"
					:key="p.id"
					:timestamp="`${p.created_at} / ${p.user.name}`"
					placement="top"
				>
					<strong>{{p.jenis_detail_pekerjaan.nama}}</strong>
					<el-progress :percentage="p.prosentase_pekerjaan"></el-progress>
					<br />
					<p>{{p.keterangan}}</p>
				</el-timeline-item>
			</el-timeline>-->
			<ul class="list-unstyled">
				<li class="media mb-4" v-for="p in order.order_progress" :key="p.id">
					<el-avatar
						:size="45"
						icon="el-icon-user-solid"
						class="mr-3"
					></el-avatar>
					<div class="media-body">
						<div class="text-muted mb-2">
							<strong>{{ p.user.name }}</strong>
							<br />
							{{ p.created_at }}
						</div>
						<h5 class="mt-0 mb-1">{{ p.jenis_detail_pekerjaan.nama }}</h5>
						<el-progress :percentage="p.prosentase_pekerjaan"></el-progress>
						<p class="mt-3 mb-3" v-html="p.keterangan"></p>
					</div>
				</li>
			</ul>
		</el-card>
	</div>
</template>

<script>
import { mapState } from "vuex";

export default {
	props: ["order"],
	computed: {
		...mapState(["listJenisDetailPekerjaan"]),
	},
	mounted() {
		this.reset();
	},
	data() {
		return {
			formModel: {},
			formError: {},
		};
	},
	methods: {
		getPercentage() {
			let detail = this.order.order_detail.find(
				(d) =>
					d.jenis_detail_pekerjaan_id ==
					this.formModel.jenis_detail_pekerjaan_id
			);

			if (detail) {
				this.formModel.prosentase_pekerjaan = detail.prosentase_pekerjaan;
			}
		},

		reset() {
			this.formModel = {
				order_id: this.order.id,
				prosentase_pekerjaan: 0,
				status: this.order.status,
				tanggal_keluar: this.order.tanggal_keluar,
			};
		},

		submit() {
			this.$axios
				.post("/api/orderProgress", this.formModel)
				.then((r) => {
					this.$message({
						message: r.data.message,
						type: "success",
					});
					this.$emit("reload-data");
					this.$store.dispatch("getListJenisDetailPekerjaan");
					this.$nextTick(() => {
						this.reset();
					});
					this.formError = {};
				})
				.catch((e) => {
					if (e.response.status == 422) {
						this.formError = e.response.data.errors;
					}

					this.$message({
						message: e.response.data.message,
						type: "error",
					});
				});
		},

		removeJenisDetailPekerjaan(id) {
			this.$confirm("Anda yakin akan menghapus item ini?", "Perhatian")
				.then(() => {
					this.$axios.delete(`/api/jenisDetailPekerjaan/${id}`).then((r) => {
						this.formModel.jenis_detail_pekerjaan_id = "";
						this.$store.dispatch("getListJenisDetailPekerjaan");
					});
				})
				.catch(() => console.log(e));
		},
	},
};
</script>

<style>
</style>
