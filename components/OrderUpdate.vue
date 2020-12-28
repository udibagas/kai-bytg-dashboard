<template>
	<div>
		<el-card header="Update Progress" class="mb-3">
      <el-table :data="formModel.checklist" stripe>
        <el-table-column label="#" prop="urutan" width="50"></el-table-column>
        <el-table-column label="Item Pekerjaan" prop="nama"></el-table-column>
        <el-table-column label="Prosentase" align="center" header-align="center">
          <template slot-scope="scope">{{scope.row.bobot}}%</template>
        </el-table-column>
        <el-table-column label="Checklist" align="center" header-align="center" width="100">
          <template slot-scope="scope">
            <el-checkbox v-model="scope.row.check"></el-checkbox>
          </template>
        </el-table-column>
      </el-table>

      <br>

			<el-form label-position="left" label-width="200px">
				<el-form-item
					label="Prosentase Pekerjaan"
					:class="formError.prosentase_pekerjaan ? 'is-error' : ''"
				>
					<div class="d-flex">
						<el-slider
							class="flex-grow-1 mr-3"
							v-model="formModel.prosentase_pekerjaan"
							:step="5"
              disabled
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

        <el-form-item label="Posisi">
					<el-input placeholder="Posisi" v-model="formModel.posisi"></el-input>
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
						<el-progress :percentage="p.prosentase_pekerjaan"></el-progress>
            <div class="mt-3 mb-3">
              Posisi : {{p.posisi}} <br>
              <span v-html="p.keterangan"></span>
            </div>
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

  watch: {
    'formModel.checklist': {
      deep: true,
      handler(v) {
        this.formModel.prosentase_pekerjaan = v.filter(c => c.check).reduce((total, item) => total + item.bobot, 0);
        this.$forceUpdate();
      }
    }
  },

	computed: {
		...mapState(["listItemPekerjaan"]),
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

		reset() {
      let checklist = this.$store.state.listItemPekerjaan.map(i => {
        const {id, nama, bobot, urutan } = i;
        return { id, nama, bobot, urutan, check: false }
      });

      if (this.order.order_progress && this.order.order_progress[0].checklist != null) {
        checklist = this.order.order_progress[0].checklist
      };

			this.formModel = {
				order_id: this.order.id,
				prosentase_pekerjaan: this.order.prosentase_pekerjaan,
				status: this.order.status,
        tanggal_keluar: this.order.tanggal_keluar,
        checklist
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
					this.reset();
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
	},
};
</script>
