<template>
  <v-row justify="center" align="center">
    <v-overlay :value="loading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>

    <v-col cols="12" md="12">
      <v-card>
        <v-overlay :value="loading">
          <v-progress-circular indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <v-card-title>
          <span class="headline">{{ formTitle }}</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" md="3">
                <v-text-field
                  counter
                  outlined
                  v-model="form.codigo"
                  type="text"
                  label="Código"
                  data-vv-scope="create"
                  data-vv-name="código"
                  v-validate="'required|max:15|min:1'"
                ></v-text-field>
                <FormError
                  :attribute_name="'create.código'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="9">
                <v-text-field
                  counter
                  outlined
                  v-model="form.supplier_id"
                  type="text"
                  label="Nombre"
                  data-vv-scope="create"
                  data-vv-name="nombre"
                  v-validate="'required|max:50|min:2'"
                ></v-text-field>
                <FormError
                  :attribute_name="'create.nombre'"
                  :errors_form="errors"
                ></FormError>
                <ul v-for="(item, index) in filteredList" v-bind:key="index">
                  <li>
                    <a @click="seleccionar_proveedor(item)">{{ item.name }}</a>
                  </li>
                </ul>
              </v-col>
              <v-col cols="12" md="10">
                <v-autocomplete
                  v-model="detail.product_id"
                  :items="products"
                  :loading="loading_product"
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
                <br />
                <v-btn color="blue darken-1" @click="getProduct()">
                  Actaulizar
                </v-btn>
              </v-col>
              <v-col cols="12" md="3" class="text-center">
                <label>Costo</label>
                <input
                  dark
                  placeholder="Costo"
                  class="nuevo-input"
                  v-model="detail.cost"
                  @input="detail.cost = formatear_numero($event)"
                  data-vv-name="costo"
                  v-validate="'required'"
                  data-vv-scope="detail"
                />
                <FormError
                  :attribute_name="'detail.costo'"
                  :errors_form="errors"
                ></FormError>
              </v-col>
              <v-col cols="12" md="3" class="text-center">
                <label>Cantidad</label>
                <vue-number-input
                  v-model="detail.new_incomes"
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
              <v-col cols="12" md="3" class="text-center">
                <br />
                <v-btn color="blue darken-1" @click="detail_add('detail')">
                  Agregar
                </v-btn>
              </v-col>
              <v-col cols="12">
                <hr />
              </v-col>
              <v-col cols="12">
                <v-simple-table dark>
                  <template v-slot:default>
                    <thead>
                      <tr>
                        <th class="text-center">Código</th>
                        <th class="text-center">Producto</th>
                        <th class="text-center">Precio Costo</th>
                        <th class="text-center">Cantidad</th>
                        <th class="text-center">Opción</th>
                      </tr>
                    </thead>
                    <tbody v-if="form.incomes.length > 0">
                      <tr v-for="(item, index) in form.incomes" :key="index">
                        <td class="text-center">
                          {{ form.codigo }}
                        </td>
                        <td class="text-center">{{ item.product_id.name }}</td>
                        <td class="text-center">
                          {{ item.cost }}
                        </td>
                        <td class="text-center">
                          {{ item.new_incomes }}
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
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="close">Cancelar</v-btn>
          <v-btn
            color="blue darken-1"
            text
            @click="validar_formulario('create')"
          >
            Guardar
          </v-btn>
        </v-card-actions>
      </v-card>
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
            <v-toolbar-title>Compras</v-toolbar-title>
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

<style scoped>
.quill-editor,
.content {
  background-color: white;
  color: black;
}
</style>

<script>
import FormError from '../shared/FormError'

export default {
  name: 'Income',
  components: {
    FormError,
  },
  data() {
    return {
      loading: false,
      loading_product: false,
      search: '',
      headers: [
        {
          text: 'Código',
          align: 'start',
          value: 'codigo',
        },
        {
          text: 'Proveedor',
          align: 'start',
          value: 'supplier.name',
        },
        {
          text: 'Producto',
          align: 'start',
          value: 'product.name',
        },
        {
          text: 'Cantidad',
          align: 'start',
          value: 'new_incomes',
        },
        {
          text: 'Costo',
          align: 'start',
          value: 'monto',
        },
        {
          text: 'Fecha de Ingreso',
          align: 'start',
          value: 'created_at',
        },
        {
          text: 'Usuario',
          align: 'start',
          value: 'user.full_name',
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
      products: [],
      suppliers: [],
      form: {
        supplier_id: null,
        incomes: [],
        codigo: null,
      },
      detail: {
        product_id: null,
        cost: null,
        new_incomes: null,
      },
    }
  },
  computed: {
    formTitle() {
      return 'Agregar compra'
    },

    filteredList() {
      if (this.form.supplier_id) {
        if (this.form.supplier_id.length > 2) {
          return this.suppliers.filter((element) => {
            return element.name
              .toUpperCase()
              .includes(this.form.supplier_id.toUpperCase())
          })
        }
      } else {
        return []
      }
    },
  },

  created() {
    this.initialize()
    this.getProduct()
    this.getSupplier()
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

    limpiar() {
      this.form.supplier_id = null
      this.form.incomes = []

      this.detail.product_id = null
      this.detail.cost = null
      this.detail.new_incomes = null

      this.form.codigo = null

      this.$validator.reset()
      this.$validator.reset()
    },

    initialize() {
      this.loading = true

      this.$store.state.services.incomeService
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
          this.limpiar()
          this.loading = false
        })
        .catch((r) => {
          this.loading = false
        })
    },

    close() {
      this.limpiar()
    },

    validar_formulario(scope) {
      if (this.form.incomes.length == 0) {
        this.$toastr.warning(
          'Debe de agregar al un producto al detalle.',
          'Mensaje',
        )
        return 0
      }

      this.$validator.validateAll(scope).then((result) => {
        if (result) this.store(this.form)
      })
    },

    store(data) {
      this.$swal({
        title: 'Agregar',
        text: '¿Está seguro de realizar esta acción?',
        type: 'success',
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading = true
          this.$store.state.services.incomeService
            .store(data)
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

              this.$toastr.success(r.data, 'Mensaje')
              this.initialize()
            })
            .catch((r) => {
              this.loading = false
            })
        } else {
          this.close()
        }
      })
    },

    getProduct() {
      this.products = []
      this.loading_product = true
      this.$store.state.services.productService
        .index()
        .then((r) => {
          r.data.data.forEach((element) => {
            if (element.deleted_at == null) {
              this.products.push(element)
            }
          })

          this.loading_product = false
        })
        .catch((r) => {})
    },

    getSupplier() {
      this.$store.state.services.supplierService
        .index()
        .then((r) => {
          this.suppliers = r.data.data
        })
        .catch((r) => {})
    },

    seleccionar_proveedor(item) {
      this.form.supplier_id = item.name
    },

    detail_add(scope) {
      if (!this.form.codigo) {
        this.$toastr.warning(
          'Es necesario que ingrese el número (código) de la compra.',
          'Mensaje',
        )
        return 0
      }
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          let objeto = new Object()
          objeto.product_id = this.detail.product_id
          objeto.cost = this.detail.cost
          objeto.new_incomes = this.detail.new_incomes

          this.form.incomes.push(objeto)

          this.detail.product_id = null
          this.detail.cost = null
          this.detail.new_incomes = null

          this.$validator.reset()
          this.$validator.reset()
        }
      })
    },

    delete_detail(index) {
      this.form.incomes.splice(this.form.incomes.indexOf(index), 1)
    },
  },
}
</script>
