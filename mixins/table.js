export default {

  data() {
    return {
      tableData: [],
      showForm: false,
      selectedData: {},
      filters: { keyword: '' },
      sorter: { prop: 'id', order: 'ascending' },
      pagination: {
        current_page: 1,
        per_page: 10,
        from: 0,
        to: 0,
        total: 0
      }
    }
  },

  methods: {

    fetchData() {
      const params = {
        page: this.pagination.current_page,
        ...this.filters,
        ...this.pagination
      }

      this.$axios.get(this.url, { params }).then(r => {
        this.tableData = r.data.data
        const { current_page, per_page, from, to, total } = r.data.meta
        this.pagination = { current_page, per_page, from, to, total }
      }).catch(e => {
        this.$message({
          message: e.response.data.message,
          type: 'error'
        })
      })
    },

    editData(data) {
      this.selectedData = JSON.parse(JSON.stringify(data))
      this.showForm = true
    },

    deleteData(id) {
      this.$confirm('Anda yakin akan menghapus data ini?', 'Konfirmasi', { type: 'warning' }).then(() => {
        this.$axios.delete(`${this.url}/${id}`).then(r => {
          this.$message({
            message: r.data.message,
            type: 'success'
          });
          this.fetchData();
        }).catch(e => {
          this.$message({
            message: e.response.data.message,
            type: 'error'
          })
        })
      }).catch(e => console.log(e))
    },

    sortChange(c) {
      this.sorter = c
      this.pagination.current_page = 1
      this.fetchData()
    },

    filterChange(filter) {
      this.pagination.current_page = 1
      this.filters = { ...this.filters, ...filter }
      this.fetchData()
    },

  }
}
