<template>
	<table class="table table-striped">
		<thead>
			<tr>
				<th style="width:170px">Update Terakhir</th>
				<th style="min-width:150px">Pekerjaan</th>
				<th>Keterangan</th>
				<th style="min-width:150px">Prosentase</th>
			</tr>
		</thead>
		<tbody>
			<tr v-for="p in detail" :key="p.id">
				<td>{{p.created_at}}</td>
				<td>{{p.jenis_detail_pekerjaan.nama}}</td>
				<td>{{p.keterangan}}</td>
				<td>
					<el-progress :percentage="p.prosentase_pekerjaan"></el-progress>
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr class="bg-warning">
				<th colspan="3">TOTAL</th>
				<th>
					<el-progress :percentage="total"></el-progress>
				</th>
			</tr>
		</tfoot>
	</table>
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
};
</script>

<style>
</style>
