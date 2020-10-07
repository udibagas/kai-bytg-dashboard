<template>
	<div style="height:calc(100vh - 100px)">
		<div class="d-flex">
			<div class="flex-grow-1">
				<el-page-header title @back="$router.go(-1)" content="Kelola Program Kerja"></el-page-header>
			</div>
			<el-form inline @submit.native.prevent>
				<el-form-item>
					<el-input
						type="number"
						size="small"
						prefix-icon="el-icon-date"
						v-model="tahun"
						placeholder="Tahun"
					></el-input>
				</el-form-item>
				<el-form-item>
					<el-button size="small" type="primary" icon="el-icon-success" @click="save">SIMPAN</el-button>
				</el-form-item>
			</el-form>
		</div>

		<table class="table table-sm table-bordered" v-loading="loading" v-if="program_kerja">
			<thead>
				<tr>
					<th>Program</th>
					<th class="text-center">JAN</th>
					<th class="text-center">FEB</th>
					<th class="text-center">MAR</th>
					<th class="text-center bg-warning">TW1</th>
					<th class="text-center">APR</th>
					<th class="text-center">MEI</th>
					<th class="text-center">JUN</th>
					<th class="text-center bg-warning">TW2</th>
					<th class="text-center bg-success">SM1</th>
					<th class="text-center">JUL</th>
					<th class="text-center">AGU</th>
					<th class="text-center">SEP</th>
					<th class="text-center bg-warning">TW3</th>
					<th class="text-center">OKT</th>
					<th class="text-center">NOV</th>
					<th class="text-center">DES</th>
					<th class="text-center bg-warning">TW4</th>
					<th class="text-center bg-success">SM2</th>
					<th class="text-center bg-info">{{tahun}}</th>
				</tr>
			</thead>
			<tbody>
				<template v-for="(s,i) in listJenisSarana">
					<tr :key="i">
						<td colspan="20" class="text-bold">{{s.kode}} - {{s.nama}}</td>
					</tr>
					<tr v-for="(p,j) in listJenisPekerjaan" :key="i+'-'+j">
						<td>{{p.kode}}</td>
						<td class="text-center">
							<input type="text" v-model="program_kerja[i].program[j].target[1]" class="my-input" />
						</td>
						<td class="text-center">
							<input type="text" v-model="program_kerja[i].program[j].target[2]" class="my-input" />
						</td>
						<td class="text-center">
							<input type="text" v-model="program_kerja[i].program[j].target[3]" class="my-input" />
						</td>
						<td
							class="text-center bg-warning"
						>{{Number(program_kerja[i].program[j].target[1]) + Number(program_kerja[i].program[j].target[2]) + Number(program_kerja[i].program[j].target[3])}}</td>
						<td class="text-center">
							<input type="text" v-model="program_kerja[i].program[j].target[4]" class="my-input" />
						</td>
						<td class="text-center">
							<input type="text" v-model="program_kerja[i].program[j].target[5]" class="my-input" />
						</td>
						<td class="text-center">
							<input type="text" v-model="program_kerja[i].program[j].target[6]" class="my-input" />
						</td>
						<td
							class="text-center bg-warning"
						>{{Number(program_kerja[i].program[j].target[4]) + Number(program_kerja[i].program[j].target[5]) + Number(program_kerja[i].program[j].target[6])}}</td>
						<td
							class="text-center bg-success"
						>{{Number(program_kerja[i].program[j].target[1]) + Number(program_kerja[i].program[j].target[2]) + Number(program_kerja[i].program[j].target[3]) + Number(program_kerja[i].program[j].target[4]) + Number(program_kerja[i].program[j].target[5]) + Number(program_kerja[i].program[j].target[6])}}</td>
						<td class="text-center">
							<input type="text" v-model="program_kerja[i].program[j].target[7]" class="my-input" />
						</td>
						<td class="text-center">
							<input type="text" v-model="program_kerja[i].program[j].target[8]" class="my-input" />
						</td>
						<td class="text-center">
							<input type="text" v-model="program_kerja[i].program[j].target[9]" class="my-input" />
						</td>
						<td
							class="text-center bg-warning"
						>{{Number(program_kerja[i].program[j].target[7]) + Number(program_kerja[i].program[j].target[8]) + Number(program_kerja[i].program[j].target[9])}}</td>
						<td class="text-center">
							<input type="text" v-model="program_kerja[i].program[j].target[10]" class="my-input" />
						</td>
						<td class="text-center">
							<input type="text" v-model="program_kerja[i].program[j].target[11]" class="my-input" />
						</td>
						<td class="text-center">
							<input type="text" v-model="program_kerja[i].program[j].target[12]" class="my-input" />
						</td>
						<td
							class="text-center bg-warning"
						>{{Number(program_kerja[i].program[j].target[10]) + Number(program_kerja[i].program[j].target[11]) + Number(program_kerja[i].program[j].target[12])}}</td>
						<td
							class="text-center bg-success"
						>{{Number(program_kerja[i].program[j].target[7]) + Number(program_kerja[i].program[j].target[8]) + Number(program_kerja[i].program[j].target[9]) + Number(program_kerja[i].program[j].target[10]) + Number(program_kerja[i].program[j].target[11]) + Number(program_kerja[i].program[j].target[12])}}</td>
						<td
							class="text-center bg-info"
						>{{program_kerja[i].program[j].target.reduce((total, item) => total + Number(item), 0)}}</td>
					</tr>
				</template>
			</tbody>
			<tfoot>
				<tr>
					<th>TOTAL</th>
					<th class="text-center">{{countTargetByMonth(1)}}</th>
					<th class="text-center">{{countTargetByMonth(2)}}</th>
					<th class="text-center">{{countTargetByMonth(3)}}</th>
					<th class="bg-warning text-center">{{countTargetByQuarter(1)}}</th>
					<th class="text-center">{{countTargetByMonth(4)}}</th>
					<th class="text-center">{{countTargetByMonth(5)}}</th>
					<th class="text-center">{{countTargetByMonth(6)}}</th>
					<th class="bg-warning text-center">{{countTargetByQuarter(2)}}</th>
					<th class="bg-success text-center">{{countTargetBySemester(1)}}</th>
					<th class="text-center">{{countTargetByMonth(7)}}</th>
					<th class="text-center">{{countTargetByMonth(8)}}</th>
					<th class="text-center">{{countTargetByMonth(9)}}</th>
					<th class="bg-warning text-center">{{countTargetByQuarter(3)}}</th>
					<th class="text-center">{{countTargetByMonth(10)}}</th>
					<th class="text-center">{{countTargetByMonth(11)}}</th>
					<th class="text-center">{{countTargetByMonth(12)}}</th>
					<th class="bg-warning text-center">{{countTargetByQuarter(4)}}</th>
					<th class="bg-success text-center">{{countTargetBySemester(2)}}</th>
					<th class="bg-info text-center">{{totalTarget()}}</th>
				</tr>
			</tfoot>
		</table>
	</div>
</template>

<script>
import { mapState } from "vuex";

export default {
	data() {
		return {
			loading: true,
			tahun: new Date().getFullYear(),
			program_kerja: null,
			program_kerja_raw: [],
		};
	},
	computed: {
		...mapState(["listJenisPekerjaan", "listJenisSarana"]),
	},
	watch: {
		tahun(v) {
			if (v.length == 4) {
				this.getData();
			}
		},
	},
	methods: {
		getData() {
			const params = { tahun: this.tahun };
			this.loading = true;
			this.$axios
				.get("/api/programKerja", { params })
				.then((r) => {
					this.program_kerja_raw = r.data;
					this.parseProgramKerja(r.data);
				})
				.finally(() => (this.loading = false));
		},
		countTargetByMonth(bulan) {
			return this.program_kerja_raw
				.filter((p) => p.bulan == bulan)
				.reduce((total, current) => total + current.target, 0);
		},
		countTargetByQuarter(quarter) {
			return this.program_kerja_raw
				.filter((p) => {
					let q = [
						[1, 2, 3],
						[4, 5, 6],
						[7, 8, 9],
						[10, 11, 12],
					];

					return q[quarter - 1].includes(p.bulan);
				})
				.reduce((total, current) => total + current.target, 0);
		},
		countTargetBySemester(semester) {
			return this.program_kerja_raw
				.filter((p) => {
					let s = [
						[1, 2, 3, 4, 5, 6],
						[7, 8, 9, 10, 11, 12],
					];

					return s[semester - 1].includes(p.bulan);
				})
				.reduce((total, current) => total + current.target, 0);
		},
		totalTarget() {
			return this.program_kerja_raw.reduce(
				(total, current) => total + current.target,
				0
			);
		},
		parseProgramKerja(target = null) {
			this.program_kerja = this.listJenisSarana.map((s) => {
				const program = this.listJenisPekerjaan.map((p) => {
					const defaultTarget = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
					let currentTarget = null;

					if (target) {
						currentTarget = defaultTarget.map((t, m) => {
							const x = target.find((tg) => {
								return (
									tg.jenis_pekerjaan_id == p.id &&
									tg.jenis_sarana_id == s.id &&
									tg.bulan == m
								);
							});
							return x ? x.target : 0;
						});
					}

					return {
						jenis_pekerjaan_id: p.id,
						target: currentTarget || defaultTarget,
					};
				});

				return {
					jenis_sarana_id: s.id,
					program: program,
				};
			});

			console.log(this.program_kerja);

			this.loading = false;
		},
		save() {
			this.$axios
				.post("/api/programKerja", {
					tahun: this.tahun,
					program_kerja: this.program_kerja,
				})
				.then((r) => {
					this.$message({
						message: r.data.message,
						type: "success",
						showClose: true,
						duration: 10000,
					});

					this.getData();
				});
		},
	},
	created() {
		this.getData();
		this.parseProgramKerja();
	},
};
</script>

<style lang="css" scoped>
input.my-input {
	border: none;
	width: 40px;
	text-align: center;
}
</style>
