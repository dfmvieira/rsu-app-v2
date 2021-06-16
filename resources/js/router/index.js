import Vue from 'vue'
import Router from 'vue-router'

// Containers
const TheContainer = () => import('../containers/TheContainer.vue')

// Views
const Dashboard = () => import('../views/Dashboard.vue')

const Colors = () => import('../views/theme/Colors.vue')
const Typography = () => import('../views/theme/Typography.vue')

const Charts = () => import('../views/charts/Charts.vue')
const Widgets = () => import('../views/widgets/Widgets.vue')

// Views - Components
const Map = () => import('../views/map/Map.vue')
const InsertSign = () => import('../views/map/InsertSign.vue')
const ViennaSigns = () => import('../views/viennaSigns/ViennaSigns.vue')
const ViennaSignsCategories = () => import('../views/viennaSigns/ViennaSignsCategories.vue')
const InsertViennaSign = () => import('../views/viennaSigns/InsertViennaSign.vue')

// Views - Buttons
const StandardButtons = () => import('../views/buttons/StandardButtons.vue')
const ButtonGroups = () => import('../views/buttons/ButtonGroups.vue')
const Dropdowns = () => import('../views/buttons/Dropdowns.vue')
const BrandButtons = () => import('../views/buttons/BrandButtons.vue')

// Views - Icons
const CoreUIIcons = () => import('../views/icons/CoreUIIcons.vue')
const Brands = () => import('../views/icons/Brands.vue')
const Flags = () => import('../views/icons/Flags.vue')

// Views - Notifications
const Alerts = () => import('../views/notifications/Alerts.vue')
const Badges = () => import('../views/notifications/Badges.vue')
const Modals = () => import('../views/notifications/Modals.vue')

// Views - Pages
const Page404 = () => import('../views/pages/Page404.vue')
const Page500 = () => import('../views/pages/Page500.vue')
const Login = () => import('../views/pages/Login.vue')
const Register = () => import('../views/pages/Register.vue')

// Users
const Users = () => import('../views/users/Users.vue')
const User = () => import('../views/users/User.vue')
const EditUser = () => import('../views/users/EditUser.vue')

//Notes
const Notes = () => import('../views/notes/Notes.vue')
const Note = () => import('../views/notes/Note.vue')
const EditNote = () => import('../views/notes/EditNote.vue')
const CreateNote = () => import('../views/notes/CreateNote.vue')

//Roles
const Roles = () => import('../views/roles/Roles.vue')
const Role = () => import('../views/roles/Role.vue')
const EditRole = () => import('../views/roles/EditRole.vue')
const CreateRole = () => import('../views/roles/CreateRole.vue')

//Bread
const Breads = () => import('../views/bread/Breads.vue')
const Bread = () => import('../views/bread/Bread.vue')
const EditBread = () => import('../views/bread/EditBread.vue')
const CreateBread = () => import('../views/bread/CreateBread.vue')
const DeleteBread = () => import('../views/bread/DeleteBread.vue')

//Resources
const Resources = () => import('../views/resources/Resources.vue')
const CreateResource = () => import('../views/resources/CreateResources.vue')
const Resource = () => import('../views/resources/Resource.vue')
const EditResource = () => import('../views/resources/EditResource.vue')
const DeleteResource = () => import('../views/resources/DeleteResource.vue')

//Email
const Emails        = () => import('../views/email/Emails.vue')
const CreateEmail   = () => import('../views/email/CreateEmail.vue')
const EditEmail     = () => import('../views/email/EditEmail.vue')
const ShowEmail     = () => import('../views/email/ShowEmail.vue')
const SendEmail     = () => import('../views/email/SendEmail.vue')

const Menus       = () => import('../views/menu/MenuIndex.vue')
const CreateMenu  = () => import('../views/menu/CreateMenu.vue')
const EditMenu    = () => import('../views/menu/EditMenu.vue')
const DeleteMenu  = () => import('../views/menu/DeleteMenu.vue')

const MenuElements = () => import('../views/menuElements/ElementsIndex.vue')
const CreateMenuElement = () => import('../views/menuElements/CreateMenuElement.vue')
const EditMenuElement = () => import('../views/menuElements/EditMenuElement.vue')
const ShowMenuElement = () => import('../views/menuElements/ShowMenuElement.vue')
const DeleteMenuElement = () => import('../views/menuElements/DeleteMenuElement.vue')

const Media = () => import('../views/media/Media.vue')


Vue.use(Router)

let router = new Router({
  mode: 'hash', // https://router.vuejs.org/api/#mode
  linkActiveClass: 'active',
  scrollBehavior: () => ({ y: 0 }),
  routes: configRoutes()
})


router.beforeEach((to, from, next) => {
  let roles = localStorage.getItem("roles");
  if(roles != null){
    roles = roles.split(',')
  }
  if(to.matched.some(record => record.meta.requiresAdmin)) {
    if(roles != null && roles.indexOf('admin') >= 0 ){
      next()
    }else{
      next({
        path: '/login',
        params: { nextUrl: to.fullPath }
      })
    }
  }else if(to.matched.some(record => record.meta.requiresUser)) {
    if(roles != null && roles.indexOf('user') >= 0 ){
      next()
    }else{
      next({
        path: '/login',
        params: { nextUrl: to.fullPath }
      })
    }
  }else{
    next()
  }
})

export default router

function configRoutes () {
  return [
    {
      path: '/',
      redirect: '/dashboard',
      name: 'Home',
      component: TheContainer,
      children: [
        {
          path: 'media',
          name: 'Media',
          component: Media,
          meta:{
            requiresAdmin: true
          }
        },
        {
          path: 'dashboard',
          name: 'Dashboard',
          component: Dashboard
        },
        {
          path: 'colors',
          name: 'Colors',
          component: Colors,
          meta:{
            requiresUser: true
          }
        },
        {
          path: 'typography',
          name: 'Typography',
          component: Typography,
          meta:{
            requiresUser: true
          }
        },
        {
          path: 'charts',
          name: 'Charts',
          component: Charts,
          meta:{
            requiresUser: true
          }
        },
        {
          path: 'widgets',
          name: 'Widgets',
          component: Widgets,
          meta:{
            requiresUser: true
          }
        },
        {
          path: 'vienna',
          meta: { label: 'vienna'},
          component: {
            render (c) {return c('router-view')}
          },
          children: [
            {
              path: '',
              component: ViennaSigns,
              meta: {
                requiresAdmin: false
              }
            },
            {
              path: '/vienna/add',
              component: InsertViennaSign,
              meta: {
                requiresAdmin: false
              }
            },
            {
              path: '/signscategories',
              component: ViennaSignsCategories,
              meta: {
                requiresAdmin: false
              }
            }
          ]
        },
        {
          path: 'menu',
          meta: { label: 'Menu'},
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              component: Menus,
              meta:{
                requiresAdmin: true
              }
            },
            {
              path: 'create',
              meta: { label: 'Create Menu' },
              name: 'CreateMenu',
              component: CreateMenu,
              meta:{
                requiresAdmin: true
              }
            },
            {
              path: ':id/edit',
              meta: { label: 'Edit Menu' },
              name: 'EditMenu',
              component: EditMenu,
              meta:{
                requiresAdmin: true
              }
            },
            {
              path: ':id/delete',
              meta: { label: 'Delete Menu' },
              name: 'DeleteMenu',
              component: DeleteMenu,
              meta:{
                requiresAdmin: true
              }
            },
          ]
        },
        {
          path: 'menuelement',
          meta: { label: 'MenuElement'},
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: ':menu/menuelement',
              component: MenuElements,
              meta:{
                requiresAdmin: true
              }
            },
            {
              path: ':menu/menuelement/create',
              meta: { label: 'Create Menu Element' },
              name: 'Create Menu Element',
              component: CreateMenuElement,
              meta:{
                requiresAdmin: true
              }
            },
            {
              path: ':menu/menuelement/:id',
              meta: { label: 'Menu Element Details'},
              name: 'Menu Element',
              component: ShowMenuElement,
              meta:{
                requiresAdmin: true
              }
            },
            {
              path: ':menu/menuelement/:id/edit',
              meta: { label: 'Edit Menu Element' },
              name: 'Edit Menu Element',
              component: EditMenuElement,
              meta:{
                requiresAdmin: true
              }
            },
            {
              path: ':menu/menuelement/:id/delete',
              meta: { label: 'Delete Menu Element' },
              name: 'Delete Menu Element',
              component: DeleteMenuElement,
              meta:{
                requiresAdmin: true
              }
            },
          ]
        },
        {
          path: 'users',
          meta: { label: 'Users'},
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              component: Users,
              meta:{
                requiresAdmin: true
              }
            },
            {
              path: ':id',
              meta: { label: 'User Details'},
              name: 'User',
              component: User,
              meta:{
                requiresAdmin: true
              }
            },
            {
              path: ':id/edit',
              meta: { label: 'Edit User' },
              name: 'Edit User',
              component: EditUser,
              meta:{
                requiresAdmin: true
              }
            },
          ]
        },
        {
          path: 'notes',
          meta: { label: 'Notes'},
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              component: Notes,
              meta:{
                requiresUser: true
              }
            },
            {
              path: 'create',
              meta: { label: 'Create Note' },
              name: 'Create Note',
              component: CreateNote,
              meta:{
                requiresUser: true
              }
            },
            {
              path: ':id',
              meta: { label: 'Note Details'},
              name: 'Note',
              component: Note,
              meta:{
                requiresUser: true
              }
            },
            {
              path: ':id/edit',
              meta: { label: 'Edit Note' },
              name: 'Edit Note',
              component: EditNote,
              meta:{
                requiresUser: true
              }
            },
          ]
        },
        {
          path: 'roles',
          meta: { label: 'Roles'},
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              component: Roles,
              meta:{
                requiresAdmin: true
              }
            },
            {
              path: 'create',
              meta: { label: 'Create Role' },
              name: 'Create Role',
              component: CreateRole,
              meta:{
                requiresAdmin: true
              }
            },
            {
              path: ':id',
              meta: { label: 'Role Details'},
              name: 'Role',
              component: Role,
              meta:{
                requiresAdmin: true
              }
            },
            {
              path: ':id/edit',
              meta: { label: 'Edit Role' },
              name: 'Edit Role',
              component: EditRole,
              meta:{
                requiresAdmin: true
              }
            },
          ]
        },
        {
          path: 'bread',
          meta: { label: 'Bread'},
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              component: Breads,
              meta:{
                requiresAdmin: true
              }
            },
            {
              path: 'create',
              meta: { label: 'Create Bread' },
              name: 'CreateBread',
              component: CreateBread,
              meta:{
                requiresAdmin: true
              }
            },
            {
              path: ':id',
              meta: { label: 'Bread Details'},
              name: 'Bread',
              component: Bread,
              meta:{
                requiresAdmin: true
              }
            },
            {
              path: ':id/edit',
              meta: { label: 'Edit Bread' },
              name: 'Edit Bread',
              component: EditBread,
              meta:{
                requiresAdmin: true
              }
            },
            {
              path: ':id/delete',
              meta: { label: 'Delete Bread' },
              name: 'Delete Bread',
              component: DeleteBread,
              meta:{
                requiresAdmin: true
              }
            },
          ]
        },
        {
          path: 'email',
          meta: { label: 'Emails'},
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              component: Emails,
              meta:{
                requiresAdmin: true
              }
            },
            {
              path: 'create',
              meta: { label: 'Create Email Template' },
              name: 'Create Email Template',
              component: CreateEmail,
              meta:{
                requiresAdmin: true
              }
            },
            {
              path: ':id',
              meta: { label: 'Show Email Template'},
              name: 'Show Email Tempalte',
              component: ShowEmail,
              meta:{
                requiresAdmin: true
              }
            },
            {
              path: ':id/edit',
              meta: { label: 'Edit Email Tempalate' },
              name: 'Edit Email Template',
              component: EditEmail,
              meta:{
                requiresAdmin: true
              }
            },
            {
              path: ':id/sendEmail',
              meta: { label: 'Send Email' },
              name: 'Send Email',
              component: SendEmail,
              meta:{
                requiresAdmin: true
              }
            },
          ]
        },
        {
          path: 'resource',
          meta: { label: 'Resources'},
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: ':bread/resource',
              component: Resources,
            },
            {
              path: ':bread/resource/create',
              meta: { label: 'Create Resource' },
              name: 'CreateResource',
              component: CreateResource
            },
            {
              path: ':bread/resource/:id',
              meta: { label: 'Resource Details'},
              name: 'Resource',
              component: Resource,
            },
            {
              path: ':bread/resource/:id/edit',
              meta: { label: 'Edit Resource' },
              name: 'Edit Resource',
              component: EditResource
            },
            {
              path: ':bread/resource/:id/delete',
              meta: { label: 'Delete Resource' },
              name: 'Delete Resource',
              component: DeleteResource
            },
          ]
        },
        /* {
          path: 'base',
          redirect: '/base/cards',
          name: 'Base',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'cards',
              name: 'Cards',
              component: Cards,
              meta:{
                requiresUser: true
              }
            },
            {
              path: 'forms',
              name: 'Forms',
              component: Forms,
              meta:{
                requiresUser: true
              }
            },
            {
              path: 'switches',
              name: 'Switches',
              component: Switches,
              meta:{
                requiresUser: true
              }
            },
            {
              path: 'tables',
              name: 'Tables',
              component: Tables,
              meta:{
                requiresUser: true
              }
            },
            {
              path: 'tabs',
              name: 'Tabs',
              component: Tabs,
              meta:{
                requiresUser: true
              }
            },
            {
              path: 'breadcrumb',
              name: 'Breadcrumb',
              component: Breadcrumbs,
              meta:{
                requiresUser: true
              }
            },
            {
              path: 'carousel',
              name: 'Carousel',
              component: Carousels,
              meta:{
                requiresUser: true
              }
            },
            {
              path: 'collapse',
              name: 'Collapse',
              component: Collapses,
              meta:{
                requiresUser: true
              }
            },
            {
              path: 'jumbotron',
              name: 'Jumbotron',
              component: Jumbotrons,
              meta:{
                requiresUser: true
              }
            },
            {
              path: 'list-group',
              name: 'List Group',
              component: ListGroups,
              meta:{
                requiresUser: true
              }
            },
            {
              path: 'navs',
              name: 'Navs',
              component: Navs,
              meta:{
                requiresUser: true
              }
            },
            {
              path: 'navbars',
              name: 'Navbars',
              component: Navbars,
              meta:{
                requiresUser: true
              }
            },
            {
              path: 'pagination',
              name: 'Pagination',
              component: Paginations,
              meta:{
                requiresUser: true
              }
            },
            {
              path: 'popovers',
              name: 'Popovers',
              component: Popovers,
              meta:{
                requiresUser: true
              }
            },
            {
              path: 'progress',
              name: 'Progress',
              component: ProgressBars,
              meta:{
                requiresUser: true
              }
            },
            {
              path: 'tooltips',
              name: 'Tooltips',
              component: Tooltips,
              meta:{
                requiresUser: true
              }
            }
          ]
        }, */
        {
          path: 'buttons',
          redirect: '/buttons/standard-buttons',
          name: 'Buttons',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'buttons',
              name: 'Standard Buttons',
              component: StandardButtons,
              meta:{
                requiresUser: true
              }
            },
            {
              path: 'button-group',
              name: 'Button Group',
              component: ButtonGroups,
              meta:{
                requiresUser: true
              }
            },
            {
              path: 'dropdowns',
              name: 'Dropdowns',
              component: Dropdowns,
              meta:{
                requiresUser: true
              }
            },
            {
              path: 'brand-buttons',
              name: 'Brand Buttons',
              component: BrandButtons,
              meta:{
                requiresUser: true
              }
            }
          ]
        },
        {
          path: 'icon',
          redirect: '/icons/coreui-icons',
          name: 'CoreUI Icons',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'coreui-icons',
              name: 'Icons library',
              component: CoreUIIcons,
              meta:{
                requiresUser: true
              }
            },
            {
              path: 'brands',
              name: 'Brands',
              component: Brands,
              meta:{
                requiresUser: true
              }
            },
            {
              path: 'flags',
              name: 'Flags',
              component: Flags,
              meta:{
                requiresUser: true
              }
            }
          ]
        },
        {
          path: 'notifications',
          redirect: '/notifications/alerts',
          name: 'Notifications',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: 'alerts',
              name: 'Alerts',
              component: Alerts,
              meta:{
                requiresUser: true
              }
            },
            {
              path: 'badge',
              name: 'Badge',
              component: Badges,
              meta:{
                requiresUser: true
              }
            },
            {
              path: 'modals',
              name: 'Modals',
              component: Modals,
              meta:{
                requiresUser: true
              }
            }
          ]
        }
      ]
    },
    {
      path: '/pages',
      redirect: '/pages/404',
      name: 'Pages',
      component: {
        render (c) { return c('router-view') }
      },
      children: [
        {
          path: '404',
          name: 'Page404',
          component: Page404
        },
        {
          path: '500',
          name: 'Page500',
          component: Page500
        },
      ]
    },
    {
      path: '/',
      redirect: '/login',
      name: 'Auth',
      component: {
        render (c) { return c('router-view') }
      },
      children: [
        {
          path: 'login',
          name: 'Login',
          component: Login
        },
        {
          path: 'register',
          name: 'Register',
          component: Register
        },
      ]
    },
    {
      path: '*',
      name: '404',
      component: Page404
    }
  ]
}
