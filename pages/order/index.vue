<template>
	<div>
		<div class="d-flex">
			<div class="flex-grow-1">
				<el-page-header
					title
					@back="$router.go(-1)"
					content="Kelola Order"
				></el-page-header>
			</div>
			<el-form inline @submit.native.prevent>
				<el-form-item>
					<div class="input-group" style="margin-top: 5px">
						<div class="custom-file">
							<input
								type="file"
								class="custom-file-input"
								id="input-file"
								@change="readFile"
							/>
							<label class="custom-file-label" for="input-file"
								>Import File</label
							>
						</div>
					</div>
				</el-form-item>
				<el-form-item>
					<el-button
						size="small"
						type="primary"
						icon="el-icon-download"
						@click="exportOrder"
						:loading="exportInProgress"
						>EXPORT</el-button
					>
				</el-form-item>
				<el-form-item>
					<el-button
						size="small"
						type="primary"
						icon="el-icon-s-order"
						@click="openForm(null)"
						>INPUT</el-button
					>
				</el-form-item>
				<el-form-item>
					<el-input
						@change="
							pagination.current_page = 1;
							getData();
						"
						size="small"
						prefix-icon="el-icon-search"
						v-model="keyword"
						placeholder="Cari"
						clearable
					></el-input>
				</el-form-item>
			</el-form>
		</div>

		<el-table
			:data="tableData"
			height="calc(100vh - 215px)"
			stripe
			v-loading="loading"
			@sort-change="sortChange"
			@filter-change="
				(f) => {
					let c = Object.keys(f)[0];
					filters[c] = Object.values(f[c]);
					pagination.current_page = 1;
					getData();
				}
			"
			:default-sort="{ prop: sort, order: order }"
		>
			<el-table-column
				type="index"
				label="#"
				:index="pagination.from"
			></el-table-column>
			<el-table-column
				label="Status"
				min-width="160"
				align-header="center"
				align="center"
				sortable="custom"
			>
				<template slot-scope="scope">
					<el-tag
						effect="dark"
						:type="scope.row.status_type"
						size="small"
						style="width: 100%"
						>{{ scope.row.status_label }}</el-tag
					>
				</template>
			</el-table-column>
			<el-table-column
				label="Prosentase"
				min-width="150"
				align-header="center"
				align="center"
				sortable="custom"
			>
				<template slot-scope="scope">
					<el-progress
						:percentage="scope.row.prosentase_pekerjaan"
					></el-progress>
				</template>
			</el-table-column>
			<el-table-column
				prop="nomor"
				label="Nomor Order"
				min-width="140"
				align-header="center"
				align="center"
				sortable="custom"
			></el-table-column>
			<el-table-column
				label="Nomor Sarana"
				min-width="140"
				column-key="jenis_sarana_id"
				:filters="filterJenisSarana"
			>
				<template slot-scope="scope">
					<router-link :to="`/order/${scope.row.id}`">
						{{ scope.row.jenis_sarana }} {{ scope.row.nomor_sarana }}
					</router-link>
				</template>
			</el-table-column>

			<!-- <el-table-column
				prop="jenis_sarana"
				label="Jenis Sarana"
				min-width="120"
				align-header="center"
				align="center"
			></el-table-column> -->
			<el-table-column
				prop="jenis_pekerjaan"
				label="Pekerjaan"
				min-width="100"
				align-header="center"
				align="center"
				column-key="jenis_pekerjaan_id"
				:filters="filterJenisPekerjaan"
			></el-table-column>
			<el-table-column
				prop="tanggal_masuk"
				label="Tanggal Masuk"
				min-width="150"
				align-header="center"
				align="center"
				sortable="custom"
			>
				<template slot-scope="scope">
					{{ readableDate(scope.row.tanggal_masuk) }}
				</template>
			</el-table-column>
			<el-table-column
				prop="tanggal_keluar"
				label="Tanggal Keluar"
				min-width="150"
				align-header="center"
				align="center"
				sortable="custom"
			>
				<template slot-scope="scope">
					{{ readableDate(scope.row.tanggal_keluar) }}
				</template>
			</el-table-column>
			<el-table-column
				prop="dipo"
				label="Dipo"
				min-width="100"
				align-header="center"
				align="center"
				column-key="dipo_id"
				:filters="filterDipo"
			></el-table-column>
			<el-table-column
				prop="jalur"
				label="Jalur"
				min-width="100"
				align-header="center"
				align="center"
				column-key="jalur_id"
				:filters="filterJalur"
			></el-table-column>
			<el-table-column
				prop="keterangan"
				label="Keterangan"
				min-width="150px"
			></el-table-column>
			<el-table-column
				fixed="right"
				width="40px"
				align="center"
				header-align="center"
			>
				<template slot="header">
					<el-button
						type="text"
						@click="
							() => {
								keyword = '';
								getData();
							}
						"
						icon="el-icon-refresh"
					></el-button>
				</template>
				<template slot-scope="scope">
					<el-dropdown>
						<span class="el-dropdown-link">
							<i class="el-icon-more"></i>
						</span>
						<el-dropdown-menu slot="dropdown">
							<el-dropdown-item
								icon="el-icon-edit-outline"
								@click.native.prevent="openForm(scope.row)"
								>Edit</el-dropdown-item
							>
							<el-dropdown-item
								icon="el-icon-delete"
								@click.native.prevent="deleteData(scope.row.id)"
								>Hapus</el-dropdown-item
							>
						</el-dropdown-menu>
					</el-dropdown>
				</template>
			</el-table-column>
		</el-table>

		<div class="d-flex mt-3">
			<el-pagination
				background
				class="flex-grow-1"
				layout="total, sizes, prev, pager, next"
				:current-page="pagination.current_page"
				@current-change="
					(p) => {
						pagination.current_page = p;
						getData();
					}
				"
				@size-change="
					(s) => {
						pagination.per_page = s;
						getData();
					}
				"
				:page-size="Number(pagination.per_page)"
				:page-sizes="[10, 25, 50, 100]"
				:total="pagination.total"
			></el-pagination>
			<div class="text-right">
				<small
					>Menampilkan {{ pagination.from }} - {{ pagination.to }} dari
					{{ pagination.total }}</small
				>
			</div>
		</div>

		<OrderForm
			:data="selectedData"
			:show="showForm"
			@close-form="showForm = false"
			@refresh-data="getData"
		/>
	</div>
</template>

<script>
import XLSX from "xlsx";
import exportFromJson from "export-from-json";
import moment from "moment";
import { mapState } from "vuex";

export default {
	data() {
		return {
			loading: false,
			tableData: [],
			keyword: "",
			selectedData: {},
			showForm: false,
			exportInProgress: false,
			sort: "tanggal_masuk",
			order: "descending",
			filters: {},
			pagination: {
				current_page: 1,
				per_page: 10,
				from: 0,
				to: 0,
				total: 0,
			},
		};
	},
	computed: {
		...mapState([
			"filterJenisPekerjaan",
			"filterJenisSarana",
			"filterDipo",
			"filterJalur",
		]),
	},
	methods: {
		readableDate(date) {
			if (!date) return null;
			return moment(date).format("DD-MMM-YYYY");
		},
		sortChange(c) {
			if (c.prop != this.sort || c.order != this.order) {
				this.sort = c.prop;
				this.order = c.order;
				this.getData();
			}
		},
		readFile(oEvent) {
			var oFile = oEvent.target.files[0];
			var sFilename = oFile.name;

			var reader = new FileReader();
			var result = {};

			reader.onload = (e) => {
				var data = e.target.result;
				data = new Uint8Array(data);
				var workbook = XLSX.read(data, { type: "array" });
				var result = {};
				// see the result, caution: it works after reader event is done.
				var res = XLSX.utils
					.sheet_to_json(workbook.Sheets[workbook.SheetNames[0]], { header: 1 })
					.filter((r) => !!r[0]); // cuma yg ada datanya

				// remove header
				res.splice(0, 1);

				var dataToImport = res.map((r) => {
					return {
						jenis_sarana: r[1],
						nomor_sarana: r[2],
						dipo: r[3],
						jenis_pekerjaan: r[5],
						tanggal_masuk: r[6],
						tanggal_keluar: r[7] || null,
						prosentase_pekerjaan: r[9] || 0,
						keterangan: r[10] || " ",
					};
				});

				console.log("raw data: ", dataToImport.length);
				this.importData(dataToImport);
			};

			reader.readAsArrayBuffer(oFile);
		},
		importData(dataToImport) {
			this.loading = true;
			this.$axios
				.post("/api/order/import", { rows: dataToImport })
				.then((r) => {
					this.$message({
						message: r.data.message,
						type: "success",
					});
					this.pagination.current_page = 1;
					this.getData();
				})
				.catch((e) => {
					this.$message({
						message: e.response.data.message,
						type: "error",
						duration: 10000,
						showClose: true,
					});
				})
				.finally(() => {
					this.loading = false;
					document.getElementById("input-file").value = "";
				});
		},
		getData() {
			const params = {
				...this.pagination,
				...this.filters,
				keyword: this.keyword,
				page: this.pagination.current_page,
				sort: this.sort,
				order: this.order,
			};

			this.loading = true;
			this.$axios
				.get("/api/order", { params })
				.then((r) => {
					this.tableData = r.data.data;
					this.pagination = r.data.meta;
				})
				.finally(() => (this.loading = false));
		},
		deleteData(id) {
			this.$confirm("Anda yakin akan menghapus data ini?", "Perhatian", {
				type: "warning",
				confirmButtonText: "Ya",
				cancelButtonText: "Tidak",
			})
				.then(() => {
					this.$axios.delete(`/api/order/${id}`).then((r) => this.getData());
				})
				.catch((e) => console.log(e));
		},
		openForm(data) {
			if (data) {
				this.selectedData = JSON.parse(JSON.stringify(data));
			} else {
				this.selectedData = {
					jenis_sarana_id: "",
					dipo_id: "",
					nomor_lama: "",
				};
			}

			this.showForm = true;
		},
		exportOrder() {
			this.exportInProgress = true;
			this.$axios
				.get("/api/order/export")
				.then((r) => {
					exportFromJson({
						data: r.data,
						fileName: "bytg-order",
						exportType: "xls",
					});
				})
				.catch((e) => {
					this.$message({
						message: e.response.data.message,
						type: "error",
					});
				})
				.finally(() => (this.exportInProgress = false));
		},
	},
	mounted() {
		this.getData();
	},
};
</script>
