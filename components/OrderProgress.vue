<template>
	<div>
		<table class="table table-striped">
			<thead>
				<tr>
					<th style="width: 170px">Update Terakhir</th>
					<th style="min-width: 150px">Pekerjaan</th>
					<th>Keterangan</th>
					<th style="min-width: 150px">Prosentase</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="p in detail" :key="p.id">
					<td>{{ p.created_at }}</td>
					<td>
						{{ p.jenis_detail_pekerjaan ? p.jenis_detail_pekerjaan.nama : "" }}
					</td>
					<td>{{ p.keterangan }}</td>
					<td>
						<el-progress :percentage="p.prosentase_pekerjaan"></el-progress>
					</td>
					<td>
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
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr class="bg-warning">
					<th colspan="3">TOTAL</th>
					<th>
						<el-progress :percentage="total"></el-progress>
					</th>
					<th></th>
				</tr>
			</tfoot>
		</table>

		<OrderProgressForm
			:data="selectedData"
			:show="showForm"
			@close-form="showForm = false"
			@reload-data="$emit('reload-data')"
		/>
	</div>
</template>

<script>
export default {
	props: ["detail"],
	computed: {
		total() {
			if (this.detail.length == 0) {
				return 0;
			}

			let total =
				this.detail.reduce(
					(total, current) => total + current.prosentase_pekerjaan,
					0
				) / this.detail.length;

			return Math.round(total);
		},
	},
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
