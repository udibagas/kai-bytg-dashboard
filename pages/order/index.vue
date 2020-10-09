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
					<el-button
						size="small"
						type="primary"
						icon="el-icon-plus"
						@click="openForm(null)"
						>TAMBAH ORDER</el-button
					>
				</el-form-item>
				<el-form-item>
					<el-input
						@change="getData"
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
		>
			<el-table-column type="index" label="#"></el-table-column>
			<el-table-column
				label="Status"
				min-width="160"
				align-header="center"
				align="center"
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
			>
				<template slot-scope="scope">
					<el-progress
						:percentage="scope.row.prosentase_pekerjaan"
					></el-progress>
				</template>
			</el-table-column>
			<el-table-column
				prop="nomor"
				label="Nomor"
				min-width="120"
				align-header="center"
				align="center"
			></el-table-column>
			<el-table-column
				label="Nomor Sarana"
				min-width="120"
				align-header="center"
				align="center"
			>
				<template slot-scope="scope">
					<router-link :to="`/order/${scope.row.id}`">{{
						scope.row.nomor_sarana
					}}</router-link>
				</template>
			</el-table-column>

			<el-table-column
				prop="jenis_sarana"
				label="Jenis Sarana"
				min-width="120"
				align-header="center"
				align="center"
			></el-table-column>
			<el-table-column
				prop="jenis_pekerjaan"
				label="Jenis Pekerjaan"
				min-width="130"
				align-header="center"
				align="center"
			></el-table-column>
			<el-table-column
				prop="tanggal_masuk"
				label="Tanggal Masuk"
				min-width="150"
				align-header="center"
				align="center"
			></el-table-column>
			<el-table-column
				prop="tanggal_keluar"
				label="Tanggal Keluar"
				min-width="150"
				align-header="center"
				align="center"
			></el-table-column>
			<el-table-column
				prop="dipo"
				label="Dipo"
				min-width="100"
				align-header="center"
				align="center"
			></el-table-column>
			<el-table-column
				prop="jalur"
				label="Jalur"
				min-width="100"
				align-header="center"
				align="center"
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
				class="flex-grow-1"
				background
				@current-change="
					(p) => {
						pagination.page = p;
						getData();
					}
				"
				@size-change="
					(s) => {
						pagination.pageSize = s;
						getData();
					}
				"
				layout="total, sizes, prev, pager, next"
				:page-size="pagination.pageSize"
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
export default {
	data() {
		return {
			loading: false,
			tableData: [],
			keyword: "",
			selectedData: {},
			showForm: false,
			pagination: {
				page: 1,
				pageSize: 10,
				from: 0,
				to: 0,
				total: 0,
			},
		};
	},
	methods: {
		getData() {
			const params = { ...this.pagination, keyword: this.keyword };
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
	},
	created() {
		this.getData();
	},
};
</script>
