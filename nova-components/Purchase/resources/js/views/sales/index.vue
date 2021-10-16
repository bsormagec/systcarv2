<template>
  <loading-view
   :loading="initialLoading"
   :dusk="resourceName + '-index-component'"
  >
    <custom-index-header
      class="mb-3"
      :resource-name="resourceName"
    />
    <div class="flex">
      <div>
        <heading :level="1" class="mb-3" v-html="headingTitle" />
      </div>
      <div class="w-50 flex items-center mb-6 pl-3">
        <select class="w-full form-control form-select"
         v-model="type"
         @change="getTypesale($event)">
          <option value="normal" selected>Normal</option>
          <option value="credit">Credito</option>
          <option v-if="plan == 'oro'" value="electronic">Electronicas</option>
        </select>
      </div>
    </div>
    <div class="flex">
    <!-- Search -->
        <div class="w-1/4">
          <strong>Buscar por :</strong>
        </div>
        <div class="pr-2 w-2/5">
          <select v-model="queryFiled" class="w-full form-control form-select" id="fileds">
            <option value="control_code">Codigo Control</option>
            <option value="cuf">CUF</option>
            <option value="document">Documento</option>
            <option value="subtotal">Total</option>
            <option value="cliente">Cliente</option>
          </select>
        </div>
        <div
         class="relative h-9 flex-no-shrink mb-6"
        >
          <icon type="search" class="absolute search-icon-center ml-3 text-70" />
          <input
            data-testid="search-input"
            dusk="search"
            class="appearance-none form-search w-search pl-search shadow"
            :placeholder="__('Search')"
            type="search"
            v-model="search"
          />
        </div>
        
        <div class="w-full flex items-center mb-6">
            <custom-index-toolbar
            :resource-name="resourceName"
            />
            <button 
            class="btn btn-default btn-primary flex-no-shrink ml-auto"
            @click="gotocreate()"
            >
              Crear nuevo
            </button>
        </div>
    </div>
    <card>
      <loading-view :loading="loading">
        <div
          v-if="!sales.length"
          class="flex justify-center items-center px-6 py-8"
        >
          <div class="text-center">
            <svg
              class="mb-3"
              xmlns="http://www.w3.org/2000/svg"
              width="65"
              height="51"
              viewBox="0 0 65 51"
            >
              <path
                fill="#A8B9C5"
                d="M56 40h2c.552285 0 1 .447715 1 1s-.447715 1-1 1h-2v2c0 .552285-.447715 1-1 1s-1-.447715-1-1v-2h-2c-.552285 0-1-.447715-1-1s.447715-1 1-1h2v-2c0-.552285.447715-1 1-1s1 .447715 1 1v2zm-5.364125-8H38v8h7.049375c.350333-3.528515 2.534789-6.517471 5.5865-8zm-5.5865 10H6c-3.313708 0-6-2.686292-6-6V6c0-3.313708 2.686292-6 6-6h44c3.313708 0 6 2.686292 6 6v25.049375C61.053323 31.5511 65 35.814652 65 41c0 5.522847-4.477153 10-10 10-5.185348 0-9.4489-3.946677-9.950625-9zM20 30h16v-8H20v8zm0 2v8h16v-8H20zm34-2v-8H38v8h16zM2 30h16v-8H2v8zm0 2v4c0 2.209139 1.790861 4 4 4h12v-8H2zm18-12h16v-8H20v8zm34 0v-8H38v8h16zM2 20h16v-8H2v8zm52-10V6c0-2.209139-1.790861-4-4-4H6C3.790861 2 2 3.790861 2 6v4h52zm1 39c4.418278 0 8-3.581722 8-8s-3.581722-8-8-8-8 3.581722-8 8 3.581722 8 8 8z"
              />
            </svg>
            <h3 class="text-base text-80 font-normal mb-6">
              {{
                __('No :resource matched the given criteria.', {
                  resource: resourceName.toLowerCase(),
                })
              }}
            </h3>
            <button 
            class="btn btn-sm btn-outline inline-flex items-center focus:outline-none focus:shadow-outline active:outline-none active:shadow-outline"
             @click="gotocreate()"
             >
              Nueva Venta
            </button>
          </div>  
        </div>
        <div class="overflow-hidden overflow-x-auto relative">
          <table
            v-if="sales.length > 0"
            class="table w-full"
            cellpadding="0"
            cellspacing="0"
            data-testid="resource-table"
          >
            <thead>
              <tr>
                <th class="text-center"><span>Cliente</span></th>
                <th class="text-center"><span>Fecha</span></th>
                <th class="text-center"><span>Cº Control</span></th>
                <th class="text-center"><span>Pagada</span></th>
                <th class="text-center"><span>Estado</span></th>
                <th class="text-center"><span>Total</span></th>
                <th class="text-center"><span>Receptor</span></th>
                <th class="text-center">&nbsp;</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in sales" :key="item.id">
                <td class="text-center">{{ item.cliente.fullName }}</td>
                <td class="text-center">{{ item.fecha }}</td>
                <td class="text-center">{{ item.codigocontrol }}</td>
                <td class="text-center">{{ item.pagada }}</td>
                <td class="text-center">{{ item.estado }}</td>
                <td class="text-center">{{ item.total }}</td>
                <td class="text-center">{{ item.usuario.name }}</td>
                <td class="td-fit text-right pr-6 align-middle">
                     <div class="inline-flex items-center">
                        <!-- printf invoice Link -->
                        <button
                            @click="detailsPage(item)"
                            class="inline-flex appearance-none cursor-pointer text-70 hover:text-primary mr-3"
                            v-tooltip.click="__('Ver')"
                        >
                            <icon type="view" view-box="0 0 20 20" width="16" height="16"/>
                        </button>
                    </div>
                    <div class="inline-flex items-center">
                        <!-- printf invoice Link -->
                        <button
                            class="inline-flex appearance-none cursor-pointer text-70 hover:text-primary mr-3"
                            v-tooltip.click="__('Fact. en pdf')"
                        >
                            <icon type="download" view-box="0 0 20 20" width="16" height="16"/>
                        </button>
                    </div>
                    <div class="inline-flex items-center">
                        <!-- printf invoice Link -->
                        <button
                            class="inline-flex appearance-none cursor-pointer text-70 hover:text-primary mr-3"
                            v-tooltip.click="__('Imprimir Factura')"
                            @click="printfInvoice(item.id)"
                        >
                            <icon type="filter" view-box="0 0 20 20" width="16" height="16"/>
                        </button>
                    </div>
                    <div class="inline-flex items-center">
                        <!-- Delete Resource Link -->
                        <button
                            :dusk="`${item.id}-delete-button`"
                            class="inline-flex appearance-none cursor-pointer text-70 hover:text-primary mr-3"
                            v-tooltip.click="__('Anular Factura')"
                            @click.prevent="openDeleteModal"
                        >
                            <icon view-box="0 0 20 20" width="16" height="16"/>
                        </button>
                        <portal
                          to="modals"
                          transition="fade-transition"
                          v-if="deleteModalOpen"
                        >
                          <delete-resource-modal
                            v-if="deleteModalOpen"
                            @confirm="confirmDelete(item.id)"
                            @close="closeDeleteModal"
                            mode="delete"
                          >
                            <div slot-scope="{ uppercaseMode }" class="p-8">
                              <heading :level="2" class="mb-6">{{
                                __(uppercaseMode + ' Resource')
                              }}</heading>
                              <p class="text-80 leading-normal">
                                {{ __('Quieres anular esta venta?') }}
                              </p>
                            </div>
                          </delete-resource-modal>
                        </portal>
                    </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div>
            <pagination
                v-if="pagination.last_page > 1"
                :pagination="pagination"
                :offset="5"
                @paginate="search === '' ? getData() : searchData()"
            ></pagination>
        </div>
        
      </loading-view>
    </card>
    </loading-view>
</template>
<script type="text/javascript">
import Vue from 'vue'
import {camera} from '../../components/icons'
import {get,byMethod} from '../../lib/api'
import pagination from '../../components/partials/PaginationComponent.vue';

export default {
    components:{
      camera,
      pagination
    },
    data() {
        return {
            initialLoading: true,
            loading: true,
            type: "normal",
            plan: '',
            queryFiled: "control_code",
            search: '',
            resourceName: 'Sales',
            headingTitle:'Listado de Ventas',
            permisioncreatesale: false,
            sales: [],
            pagination: {
              current_page: 1
            },
            deleteModalOpen: false,
        }
    },
    beforeRouteEnter(to, from, next) {
      get('/nova-vendor/purchase/ventas', to.query)
          .then((res) => {
              next(vm => vm.setData(res))
          })
    },
    beforeRouteUpdate(to, from, next) {
      get('/nova-vendor/purchase/ventas', to.query)
          .then((res) => {
            this.setData(res)
              next()
          })
    },
    watch: {
      search: function(newQ, old) {
        if (newQ === "") {
          this.getData();
        } else {
          this.searchData();
        }
      }
    },
    methods: {
        getData() {
          this.loading = true;
          get("/nova-vendor/purchase/ventas/"+this.type+"/?page=" + this.pagination.current_page,{
          })
            .then(response => {
              this.sales = response.data.data;
              this.pagination = response.data.meta;
              this.loading = false;
              this.permisioncreatesale = res.data.permiso
              this.plan = res.data.plan;
            })
            .catch(e => {
              console.log(e);
            });
        },
        setData(res) {
          console.log(res)
          Vue.set(this.$data, 'sales', res.data.data)
          this.pagination = res.data.meta;
          this.permisioncreatesale = res.data.permiso
          this.plan = res.data.plan;
          this.initialLoading = false;
          this.loading = false;
        },
        searchData() {
          this.loading = true;
          get(
              "/nova-vendor/purchase/search/sales/" +
                this.queryFiled +
                "/" +
                this.type+
                "/"+
                this.search +
                "?page=" +
                this.pagination.current_page
            )
            .then(response => {
              this.sales = response.data.data;
              this.pagination = response.data.meta;
              this.loading = false;
            })
            .catch(e => {
              console.log(e);
            });
        },
        detailsPage(item) {
          let id = item.id;
          this.$router.push({ name: 'showsale', params: { id } })
        },
        gotocreate() {
          if (this.permisioncreatesale) {
            this.$router.push({name:'createsale'});
          }else{
            Nova.warning(
                this.__('Por favor inicie una caja este dia!')
                )
          }
        },
        printfInvoice(id){
          let uri = `/nova-vendor/purchase/invoices/${id}/print`; 
          var myPagina = window.open(uri,"_blank","scrollbars=yes,width=400,heigth=500,top=300");
          myPagina.focus();
          //si desea cerrarlo despues de un cierto time
          // setTimeout(function(){
          //   myPagina.close();
          // },10000);
        },
        openDeleteModal() {
          this.deleteModalOpen = true
        },
        confirmDelete(id) {
          byMethod('delete', `/nova-vendor/purchase/sales/${id}`)
                    .then((res) => {
                      console.log(res.data)
                      if(res.data.deleted) {
                        this.$toasted.show('Venta Anulada con exito', { type: 'success' });
                        this.getData();
                      }
                    })
          this.closeDeleteModal()
        },

        closeDeleteModal() {
          this.deleteModalOpen = false
        },
        getTypesale(event){
          let tipo = event.target.value;
          this.loading = true;
          get("/nova-vendor/purchase/ventas/"+this.type+"/?page=" + this.pagination.current_page,{
          })
            .then(response => {
              this.sales = response.data.data;
              this.pagination = response.data.meta;
              this.loading = false;
              this.permisioncreatesale = res.data.permiso
              this.plan = res.data.plan;
            })
            .catch(e => {
              console.log(e);
            });
        }
    }
}
</script>