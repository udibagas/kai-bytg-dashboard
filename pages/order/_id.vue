<template>
	<div class="d-flex" style="height: calc(100vh - 100px)">
		<el-card class="flex-shrink-0 mr-3" :bodyStyle="{ padding: 0 }">
			<div slot="header" class="clear-both">
				<span>Order No. {{ order.nomor }}</span>
				<el-button
					style="float: right; padding: 3px 0"
					type="text"
					icon="el-icon-refresh"
					@click="getData()"
				></el-button>
			</div>
			<table class="table table-sm table-striped" style="width: 500px">
				<tbody>
					<tr>
						<td class="td-label pl-3">Nomor Sarana</td>
						<td class="pr-4">{{ order.sarana.nomor }}</td>
					</tr>
					<tr>
						<td class="td-label pl-3">Jenis Sarana</td>
						<td>
							{{ order.jenis_sarana.kode }} - {{ order.jenis_sarana.nama }}
						</td>
					</tr>
					<tr>
						<td class="td-label pl-3">Dipo</td>
						<td class="pr-4">{{ order.dipo.nama }}</td>
					</tr>
					<tr>
						<td class="td-label pl-3">Jenis Pekerjaan</td>
						<td class="pr-4">
							{{ order.jenis_pekerjaan.kode }} -
							{{ order.jenis_pekerjaan.nama }}
						</td>
					</tr>
					<tr>
						<td class="td-label pl-3">Tanggal Masuk</td>
						<td class="pr-4">{{ readableDate(order.tanggal_masuk) }}</td>
					</tr>
					<tr>
						<td class="td-label pl-3">Tanggal Keluar</td>
						<td class="pr-4">{{ readableDate(order.tanggal_keluar) }}</td>
					</tr>
					<tr>
						<td class="td-label pl-3">Posisi</td>
						<td class="pr-4">{{ order.posisi }}</td>
					</tr>
					<tr>
						<td class="td-label pl-3">Keterangan</td>
						<td class="pr-4">{{ order.keterangan }}</td>
					</tr>
					<tr>
						<td class="td-label pl-3">Prosentase Pekerjaan</td>
						<td class="pr-4">
							<el-progress
								:percentage="order.prosentase_pekerjaan"
							></el-progress>
						</td>
					</tr>
					<tr>
						<td class="td-label pl-3">Status</td>
						<td class="pr-4">
							<el-tag effect="dark" :type="order.status_type">{{
								order.status_label
							}}</el-tag>
						</td>
					</tr>
				</tbody>
			</table>
		</el-card>
		<el-tabs type="card" class="flex-grow-1">
			<el-tab-pane label="Order Progress">
				<OrderProgress v-if="order.order_progress.length > 0" :progress="order.order_progress[0].checklist" @reload-data="getData()" />
			</el-tab-pane>
			<el-tab-pane label="Update Order">
				<OrderUpdate :order="order" @reload-data="getData()" />
			</el-tab-pane>
		</el-tabs>
	</div>
</template>

<script>
import moment from "moment";

export default {
	async asyncData({ params, $axios }) {
		const { data } = await $axios.get(`/api/order/${params.id}`);
		return { order: data };
	},
	methods: {
		readableDate(date) {
			if (!date) return null;
			return moment(date).format("DD-MMM-YYYY");
		},
		getData() {
			this.$axios.get(`/api/order/${this.$route.params.id}`).then((r) => {
				this.order = r.data;
			});
		},
	},
};
</script>

<style scoped>
.el-card__header {
	color: #0e5ca9;
	font-weight: bold;
}

.td-label {
	color: #777;
	max-width: 120px;
}
</style>
