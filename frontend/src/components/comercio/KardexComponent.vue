<template>
  <v-row justify="center" align="center">
    <v-overlay :value="loading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>

    <v-col cols="12" md="12">
      <v-data-table
        :headers="headers"
        :items="desserts"
        :search="search"
        sort-by="calories"
        class="elevation-1"
        dark
        :footer-props="footer"
      >
        <template v-slot:top>
          <v-toolbar flat color="success">
            <v-toolbar-title>Kardex</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-text-field
              v-model="search"
              append-icon="search"
              label="Buscar"
              single-line
              hide-details
            ></v-text-field>
            <v-btn class="ma-2" color="primary" @click="initialize">
              Actualizar
            </v-btn>
          </v-toolbar>
        </template>
        <template v-slot:item.status="{ item }">
          <v-chip class="ma-2" :color="item.status.color" text-color="white">
            {{ item.status.name }} - Stock {{ item.stock }}
          </v-chip>
        </template>
        <template v-slot:no-data>
          <br />
          <v-alert type="error">No hay información para mostrar.</v-alert>
        </template>
      </v-data-table>
    </v-col>
  </v-row>
</template>

<script>
import FormError from '../shared/FormError'

export default {
  name: 'Kardex',
  components: {
    FormError,
  },
  data() {
    return {
      loading: false,
      search: '',
      headers: [
        {
          text: 'Producto',
          align: 'start',
          value: 'product.name',
        },
        {
          text: 'Precio de venta',
          align: 'start',
          value: 'monto',
        },
        {
          text: 'Descuento %',
          align: 'start',
          value: 'discount',
        },
        {
          text: 'Alertar cuando falten',
          align: 'start',
          value: 'notify',
        },
        {
          text: 'Estado',
          align: 'start',
          value: 'status',
        },
        {
          text: 'Última fecha de ingreso',
          align: 'start',
          value: 'date_entry',
        },
        {
          text: 'Última fecha de egreso',
          align: 'start',
          value: 'date_egress',
        },
      ],
      footer: {
        showFirstLastPage: true,
        firstIcon: 'mdi-arrow-collapse-left',
        lastIcon: 'mdi-arrow-collapse-right',
        prevIcon: 'mdi-minus',
        nextIcon: 'mdi-plus',
      },
      desserts: [],
    }
  },

  created() {
    this.initialize()
  },

  methods: {
    initialize() {
      this.loading = true

      this.$store.state.services.kardexService
        .index()
        .then((r) => {
          if (r.response) {
            if (r.response.data.code === 423) {
              this.$toastr.error(r.response.data.error, 'Mensaje')
            } else {
              for (let value of Object.values(r.response.data.error)) {
                this.$toastr.error(value, 'Mensaje')
              }
            }
            this.loading = false
            return
          }
          this.desserts = r.data.data
          this.loading = false
        })
        .catch((r) => {
          this.loading = false
        })
    },
  },
}
</script>
