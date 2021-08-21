<template>
  <v-row justify="center" align="center">
    <v-overlay :value="loading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>

    <v-col cols="12" md="6">
      <v-card>
        <v-overlay :value="loading">
          <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <v-card-title>
          <span class="headline">Crear venta</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <!--<v-row>
              <v-col cols="12" md="12">
                <v-switch
                  v-model="por_codigo"
                  :label="por_codigo ? 'Por código' : 'Venta normal'"
                ></v-switch>
              </v-col>
            </v-row>-->
            <v-row>
              <v-col cols="12" md="12" v-if="por_codigo">
                <v-text-field
                  counter
                  outlined
                  v-model="form.authorization_code"
                  type="text"
                  label="código de autorización"
                  data-vv-scope="create"
                  data-vv-name="código de autorización"
                  v-validate="'required|max:15|min:3'"
                  @keypress.enter="validar_codigo('create')"
                ></v-text-field>
                <FormError
                  :attribute_name="'create.código de autorización'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" @click="validar_codigo('create')">
            Válidar código de usuario
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-col>

    <v-col cols="12" md="12">
      <v-dialog
        v-model="dialog"
        fullscreen
        hide-overlay
        transition="dialog-bottom-transition"
      >
        <v-card>
          <v-overlay :value="loading">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
          </v-overlay>
          <v-toolbar dark color="primary">
            <v-btn icon dark @click="dialog = false">
              <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-toolbar-title v-if="cliente_seleccionado">
              Venta para {{ cliente_seleccionado.full_name }}
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn class="ma-2" color="default" @click="getKardex">
              Actualizar
            </v-btn>
            <v-spacer></v-spacer>
            <v-toolbar-items>
              <v-btn dark text @click="store">
                Guardar
              </v-btn>
            </v-toolbar-items>
          </v-toolbar>
          <v-container>
            <v-row>
              <v-col cols="12" md="3" class="text-center">
                <label>Cantidad</label>
                <vue-number-input
                  v-model="amount"
                  :min="1"
                  :max="10000000"
                  controls
                  placeholder="Cantidad"
                  data-vv-scope="detail"
                  data-vv-name="cantidad"
                  v-validate="'required'"
                ></vue-number-input>
                <FormError
                  :attribute_name="'detail.cantidad'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="7">
                <v-autocomplete
                  v-model="product_id"
                  :items="kardex"
                  :loading="loading_kardex"
                  chips
                  label="Seleccionar producto"
                  outlined
                  :clearable="true"
                  :deletable-chips="true"
                  item-text="name"
                  item-value="id"
                  return-object
                  v-validate="'required'"
                  data-vv-scope="detail"
                  data-vv-name="producto"
                ></v-autocomplete>
                <FormError
                  :attribute_name="'detail.producto'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="2" class="text-center">
                <v-btn class="ma-2" color="info" @click="add_detail('detail')">
                  Agregar al detalle
                </v-btn>
              </v-col>
            </v-row>
          </v-container>
          <v-divider></v-divider>
          <v-simple-table dark>
            <template v-slot:default>
              <thead>
                <tr>
                  <th class="text-center">Producto</th>
                  <th class="text-center">Cantidad</th>
                  <th class="text-center">Precio</th>
                  <th class="text-center">Descuento %</th>
                  <th class="text-center">Sub Total</th>
                  <th class="text-center">Opción</th>
                </tr>
              </thead>
              <tbody v-if="form.details.length > 0">
                <tr v-for="(item, index) in form.details" :key="index">
                  <td class="text-center">{{ item.product_id.name }}</td>
                  <td class="text-center">
                    <vue-number-input
                      v-model="item.amount"
                      :min="1"
                      :max="item.product_id.stock"
                      controls
                    ></vue-number-input>
                  </td>
                  <td class="text-center">
                    <input
                      dark
                      placeholder="Precio"
                      class="nuevo-input"
                      v-model="item.price"
                      @input="item.price = formatear_numero($event)"
                    />
                  </td>
                  <td class="text-center">
                    <vue-number-input
                      v-model="item.price_discount"
                      :min="0"
                      :max="100"
                      controls
                    ></vue-number-input>
                  </td>
                  <td class="text-center">
                    {{
                      ((item.price - (item.price * item.price_discount) / 100) *
                        item.amount)
                        | currency('')
                    }}
                  </td>
                  <td class="text-center">
                    <v-btn color="error" @click="delete_detail(item)">
                      Eliminar
                    </v-btn>
                  </td>
                </tr>
              </tbody>
            </template>
          </v-simple-table>
          <v-divider></v-divider>
          <v-card-actions>
            <v-spacer></v-spacer>
            <h1>Total: {{ total | currency('') }}</h1>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-col>

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
            <v-toolbar-title>
              Productos vendidos por el usuario {{ usuario }}
            </v-toolbar-title>
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
  name: 'SaleCodeUser',
  components: {
    FormError,
  },
  data() {
    return {
      loading: false,
      loading_kardex: false,
      por_codigo: true,
      dialog: false,
      search: '',
      headers: [
        {
          text: 'Reservación',
          align: 'start',
          value: 'reservation.code',
        },
        {
          text: 'Producto',
          align: 'start',
          value: 'product.name',
        },
        {
          text: 'Cantidad',
          align: 'start',
          value: 'amount',
        },
        {
          text: 'Precio',
          align: 'start',
          value: 'monto_precio',
        },
        {
          text: 'Descuento',
          align: 'start',
          value: 'monto_descuento',
        },
        {
          text: 'Sub Total',
          align: 'start',
          value: 'monto_sub',
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
      form: {
        authorization_code: null,
        municipality_id: null,
        nit: null,
        name: null,
        email: null,
        business: null,
        ubication: null,
        way_to_pay_id: null,
        details: [],
      },
      cliente_seleccionado: null,
      kardex: [],
      productos_seleccionados: [],
      product_id: null,
      amount: 1,
    }
  },

  created() {
    this.initialize()
    this.getKardex()
  },

  computed: {
    total() {
      let total = 0
      this.form.details.forEach((x) => {
        let sub = (x.price - (x.price * x.price_discount) / 100) * x.amount
        total = total + sub
      })
      return total
    },

    usuario() {
      return this.$store.state.usuario.full_name
    },
  },

  methods: {
    formatear_numero(n) {
      if (n) {
        let numero = n.target.value.replace(',', '')
        numero = !isNaN(numero)
          ? parseInt(numero).toLocaleString('de-DE', {
              minimumFractionDigits: 0,
              maximumFractionDigits: 0,
            })
          : null
        return !isNaN(numero) ? numero.replace('.', ',') : null
      } else {
        return null
      }
    },

    initialize() {
      this.loading = true

      this.$store.state.services.reservationProductService
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

    validar_codigo(scope) {
      this.cliente_seleccionado = null
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.$swal({
            title: 'Verificar código',
            text: '¿Está seguro de realizar esta acción?',
            type: 'success',
            showCancelButton: true,
          }).then((result) => {
            if (result.value) {
              this.loading = true
              this.$store.state.services.reservationProductService
                .authorization_code(btoa(this.form.authorization_code))
                .then((r) => {
                  this.loading = false

                  if (r.response) {
                    if (r.response.data.code === 404) {
                      this.$toastr.warning(r.response.data.error, 'Advertencia')
                      return
                    } else if (r.response.data.code === 423) {
                      this.$toastr.warning(r.response.data.error, 'Advertencia')
                      return
                    } else {
                      for (let value of Object.values(r.response.data)) {
                        this.$toastr.error(value, 'Mensaje')
                      }
                    }
                    return
                  }

                  this.$toastr.success(r.data.mensaje, 'Mensaje')
                  this.cliente_seleccionado = r.data.client
                  this.dialog = true
                  this.form.details = []
                  this.product_id = null
                  this.amount = 1

                  this.$validator.reset()
                  this.$validator.reset()
                })
                .catch((r) => {
                  this.loading = false
                })
            }
          })
        }
      })
    },

    getKardex() {
      this.loading_kardex = true
      this.kardex = []
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
            this.loading_kardex = false
            return
          }

          r.data.data.forEach((element) => {
            if (element.deleted_at == null) {
              this.kardex.push({
                id: element.id,
                name: element.product.name,
                stock: element.stock,
                price: element.price,
                discount: element.discount,
              })
            }
          })

          this.loading_kardex = false
        })
        .catch((r) => {
          this.loading_kardex = false
        })
    },

    add_detail(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          if (this.product_id.stock == 0) {
            this.$toastr.error('El stock se encuentra a 0', 'Stock')
            return 0
          }

          if (this.amount > this.product_id.stock) {
            this.$toastr.warning(
              `No hay suficiente stock para cubrir la demanda solicitada, el stock actual es de ${this.product_id.stock}`,
              'Stock',
            )
            return 0
          }

          let existe = false

          let object = new Object()
          object.product_id = this.product_id
          object.amount = this.amount
          object.price = this.product_id.price
          object.price_discount = parseInt(this.product_id.discount)

          this.form.details.forEach((x) => {
            if (x.product_id.id == object.product_id.id) {
              if (x.product_id.stock >= x.amount + object.amount) {
                x.amount = x.amount + object.amount
                existe = true
              } else {
                this.$toastr.info(
                  `No hay suficiente stock para cubrir la demanda solicitada, el stock actual es de ${this.product_id.stock}`,
                  'Mensaje',
                )
                existe = true
              }
            }
          })

          if (!existe) {
            this.form.details.push(object)
          }

          this.product_id = null
          this.amount = 1

          this.$validator.reset()
          this.$validator.reset()
        }
      })
    },

    delete_detail(index) {
      this.form.details.splice(this.form.details.indexOf(index), 1)
    },

    store() {
      if (this.por_codigo == true) {
        if (!this.form.authorization_code) {
          this.$toastr.warning(
            'Es necesario que ingrese el código del usuario.',
            'Mensaje',
          )
          return 0
        }
      }

      if (this.form.details.length == 0) {
        this.$toastr.warning(
          'Es necesario que ingrese al menos un producto al detalle de la venta.',
          'Mensaje',
        )
        return 0
      }

      this.$swal({
        title: 'Agregar venta',
        text: '¿Está seguro de realizar esta acción?',
        type: 'success',
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading = true
          this.form.authorization_code = btoa(this.form.authorization_code)
          this.$store.state.services.reservationProductService
            .store(this.form)
            .then((r) => {
              this.loading = false

              if (r.response) {
                if (r.response.data.code === 404) {
                  this.$toastr.warning(r.response.data.error, 'Advertencia')
                  return
                } else if (r.response.data.code === 423) {
                  this.$toastr.warning(r.response.data.error, 'Advertencia')
                  return
                } else {
                  for (let value of Object.values(r.response.data)) {
                    this.$toastr.error(value, 'Mensaje')
                  }
                }
                return
              }

              this.$toastr.success(r.data.data, 'Mensaje')
              this.initialize()

              this.form.authorization_code = null
              this.form.details = []
              this.dialog = false

              this.$validator.reset()
              this.$validator.reset()
            })
            .catch((r) => {
              this.loading = false
            })
        }
      })
    },
  },
}
</script>
