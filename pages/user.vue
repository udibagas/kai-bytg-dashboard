<template>
	<div>
		<div class="d-flex">
			<div class="flex-grow-1">
				<el-page-header title @back="$router.go(-1)" content="Kelola User"></el-page-header>
			</div>
			<el-form inline @submit.native.prevent>
				<el-form-item>
					<el-button
						size="small"
						type="primary"
						icon="el-icon-plus"
						@click="() => { selectedData = {}; showForm = true }"
					>TAMBAH USER</el-button>
				</el-form-item>
				<el-form-item>
					<el-input
						@change="getData"
						size="small"
						prefix-icon="el-icon-search"
						v-model="keyword"
						placeholder="Cari user"
						clearable
					></el-input>
				</el-form-item>
			</el-form>
		</div>
		<el-table :data="tableData" height="calc(100vh - 165px)" stripe v-loading="loading">
			<el-table-column type="index" label="#"></el-table-column>
			<el-table-column prop="name" label="Nama"></el-table-column>
			<el-table-column prop="email" label="Email"></el-table-column>
			<el-table-column fixed="right" width="40px" align="center" header-align="center">
				<template slot="header">
					<el-button type="text" @click="() => { keyword = ''; getData(); }" icon="el-icon-refresh"></el-button>
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
							>Edit</el-dropdown-item>
							<el-dropdown-item
								icon="el-icon-delete"
								@click.native.prevent="deleteData(scope.row.id)"
							>Hapus</el-dropdown-item>
						</el-dropdown-menu>
					</el-dropdown>
				</template>
			</el-table-column>
		</el-table>
		<UserForm
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
		};
	},
	methods: {
		getData() {
			const params = { keyword: this.keyword };
			this.loading = true;
			this.$axios
				.get("/api/user", { params })
				.then((r) => {
					this.tableData = r.data.data;
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
					this.$axios.delete(`/api/user/${id}`).then((r) => this.getData());
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

<style>
</style>
