<template>
	<div>
		<div class="d-flex">
			<div class="flex-grow-1">
				<el-page-header
					title
					@back="$router.go(-1)"
					content="Kelola Sarana"
				></el-page-header>
			</div>
			<el-form inline @submit.native.prevent>
				<el-form-item>
					<el-button
						size="small"
						type="primary"
						icon="el-icon-plus"
						@click="
							() => {
								selectedData = {};
								showForm = true;
							}
						"
						>TAMBAH SARANA</el-button
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
			<el-table-column
				type="index"
				label="#"
				:index="pagination.from"
			></el-table-column>
			<el-table-column prop="nomor" label="Nomor"></el-table-column>
			<el-table-column prop="nomor_lama" label="Nomor Lama"></el-table-column>
			<el-table-column
				prop="jenis_sarana"
				label="Jenis Sarana"
			></el-table-column>
			<el-table-column prop="dipo" label="Dipo"></el-table-column>
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
								@click.native.prevent="editData(scope.row)"
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
				layout="total, sizes, prev, pager, next"
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

		<SaranaForm
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
				current_page: 1,
				per_page: 10,
				from: 0,
				to: 0,
				total: 0,
			},
		};
	},
	methods: {
		getData() {
			const params = {
				...this.pagination,
				keyword: this.keyword,
				page: this.pagination.current_page,
			};

			this.loading = true;
			this.$axios
				.get("/api/sarana", { params })
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
					this.$axios
						.delete(`/api/sarana/${id}`)
						.then((r) => this.getData())
						.catch((e) => {
							this.$message({
								message: e.response.data.message,
								type: "error",
							});
						});
				})
				.catch((e) => console.log(e));
		},
		editData(data) {
			this.selectedData = JSON.parse(JSON.stringify(data));
			this.showForm = true;
		},
	},
	created() {
		this.getData();
	},
};
</script>
