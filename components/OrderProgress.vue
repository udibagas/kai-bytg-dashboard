<template>
	<div>
		<table class="table table-striped table-sm">
			<thead>
				<tr>
          <th>#</th>
					<th style="min-width: 150px">Pekerjaan</th>
					<th class="text-center">Prosentase</th>
					<!-- <th>Keterangan</th> -->
					<th class="text-center">Check</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="p in progress[0].checklist" :key="p.id">
          <td>{{p.urutan}}</td>
					<td>{{ p.nama}}</td>
					<td class="text-center"> {{ p.bobot}}% </td>
					<!-- <td>{{ p.keterangan }}</td> -->
					<td class="text-center">
            <i v-if="p.check" class="el-icon-check text-success"></i>
            <i v-else class="el-icon-close text-danger"></i>
          </td>
					<!-- <td>
						<el-dropdown>
							<span class="el-dropdown-link">
								<i class="el-icon-more"></i>
							</span>
							<el-dropdown-menu slot="dropdown">
								<el-dropdown-item
									icon="el-icon-edit-outline"
									@click.native.prevent="
										() => {
											selectedData = JSON.parse(JSON.stringify(p));
											showForm = true;
										}
									"
									>Edit</el-dropdown-item
								>
								<el-dropdown-item
									icon="el-icon-delete"
									@click.native.prevent="deleteData(scope.row.id)"
									>Hapus</el-dropdown-item
								>
							</el-dropdown-menu>
						</el-dropdown>
					</td> -->
				</tr>
			</tbody>
		</table>

		<!-- <OrderProgressForm
			:data="selectedData"
			:show="showForm"
			@close-form="showForm = false"
			@reload-data="$emit('reload-data')"
		/> -->
	</div>
</template>

<script>
export default {
	props: ["progress"],
	data() {
		return {
			selectedData: {},
			showForm: false,
		};
	},
	methods: {
		deleteData(id) {
			this.$confirm("Anda yakin?", "Konfirmasi", { type: "warning" })
				.then(() => {
					this.$axios
						.delete(`/api/orderUpdate/${id}`)
						.then((r) => {
							this.$message({
								message: r.data.message,
								type: "success",
							});
							this.$emit("reload-data");
						})
						.catch((e) => {
							this.$message({
								message: e.response.data.message,
								type: "error",
							});
						});
				})
				.catch((e) => console.log(e));
		},
	},
};
</script>
