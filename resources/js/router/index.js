import Vue from 'vue'
import Router from 'vue-router'

// Containers
const TheContainer = () => import('../containers/TheContainer.vue')

// Views
const Dashboard = () => import('../views/Dashboard.vue')

/* const Colors = () => import('../views/theme/Colors.vue')
const Typography = () => import('../views/theme/Typography.vue') */

const Charts = () => import('../views/charts/Charts.vue')
const Widgets = () => import('../views/widgets/Widgets.vue')

// Views - Components
const Map = () => import('../views/map/Map.vue')
const InsertSign = () => import('../views/map/InsertSign.vue')
const SignInfo = () => import('../views/map/SignInfo.vue')
const ViennaSigns = () => import('../views/viennaSigns/ViennaSigns.vue')
const ViennaSignsCategories = () => import('../views/viennaSigns/ViennaSignsCategories.vue')
const InsertViennaSign = () => import('../views/viennaSigns/InsertViennaSign.vue')
const InsertViennaSignCategory = () => import('../views/viennaSigns/InsertViennaSignCategory.vue')
const IVIsigns = () => import('../views/map/IVIsigns.vue')

const Entity = () => import('../views/entity/Entity.vue')
const Entities = () => import('../views/entity/Entities.vue')
const InsertEntity = () => import('../views/entity/InsertEntity.vue')

const Signpublication = () => import('../views/signPublication/Signpublication.vue')
const PublishedSigns = () => import('../views/signPublication/PublishedSigns.vue')

const DeployGroups = () => import('../views/deployGroups/DeployGroups.vue')
const DeployGroupsUser = () => import('../views/deployGroups/DeployGroupsUser.vue')
const NewDeploy = () => import('../views/deployGroups/newDeploy.vue')

const FactorySignsToMake = () => import('../views/factory/SignsToMake.vue')

// Views - Pages
const Page404 = () => import('../views/pages/Page404.vue')
const Page500 = () => import('../views/pages/Page500.vue')
const Login = () => import('../views/pages/Login.vue')
const Register = () => import('../views/pages/Register.vue')

// Users
const Users = () => import('../views/users/Users.vue')
const User = () => import('../views/users/User.vue')
const EditUser = () => import('../views/users/EditUser.vue')
const InsertUser = () => import('../views/users/InsertUser.vue')

//Notes
/* const Notes = () => import('../views/notes/Notes.vue')
const Note = () => import('../views/notes/Note.vue')
const EditNote = () => import('../views/notes/EditNote.vue')
const CreateNote = () => import('../views/notes/CreateNote.vue')
 */
//Roles
const Roles = () => import('../views/roles/Roles.vue')
const Role = () => import('../views/roles/Role.vue')
const EditRole = () => import('../views/roles/EditRole.vue')
const CreateRole = () => import('../views/roles/CreateRole.vue')

//Bread
/* const Breads = () => import('../views/bread/Breads.vue')
const Bread = () => import('../views/bread/Bread.vue')
const EditBread = () => import('../views/bread/EditBread.vue')
const CreateBread = () => import('../views/bread/CreateBread.vue')
const DeleteBread = () => import('../views/bread/DeleteBread.vue') */

///Menu
const Menus       = () => import('../views/menu/MenuIndex.vue')
const CreateMenu  = () => import('../views/menu/CreateMenu.vue')
const EditMenu    = () => import('../views/menu/EditMenu.vue')
const DeleteMenu  = () => import('../views/menu/DeleteMenu.vue')

const MenuElements = () => import('../views/menuElements/ElementsIndex.vue')
const CreateMenuElement = () => import('../views/menuElements/CreateMenuElement.vue')
const EditMenuElement = () => import('../views/menuElements/EditMenuElement.vue')
const ShowMenuElement = () => import('../views/menuElements/ShowMenuElement.vue')
const DeleteMenuElement = () => import('../views/menuElements/DeleteMenuElement.vue')


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
          path: 'dashboard',
          name: 'Dashboard',
          component: Dashboard
        },
        {
          path: 'ivisignmap',
          meta: {label: 'Ivi Sign Map'},
          component: {
            render (c) {return c('router-view')}
          },
          children: [
            {
              path: '',
              component: Map,
              meta: {
                requiresAdmin: false
              }
            },
            {
              path: '/ivisignmap/add',
              component: InsertSign,
              meta: {
                requiresAdmin: false
              }
            },
            {
              path: '/ivisignmap/signstable',
              component: IVIsigns,
              meta: {
                requiresAdmin: false
              }
            }
          ]
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
              name: 'signscategories',
              path: '/signscategories',
              component: ViennaSignsCategories,
              meta: {
                requiresAdmin: false
              }
            },
            {
              path: '/signscategories/add',
              component: InsertViennaSignCategory,
              meta: {
                requiresAdmin: false
              }
            }
          ]
        },
        {
          path: 'entities',
          meta: { label: 'Entities'},
          component: {
            render (c) {return c('router-view')}
          },
          children: [
            {
              path: '',
              component: Entities,
              meta: {
                requiresAdmin: true
              }
            },
            {
              path: '/entities/view',
              component: Entity,
              meta: {
                requiresAdmin: false
              }
            },
            {
              path: '/entities/add',
              component: InsertEntity,
              meta: {
                requiresAdmin: true
              }
            },
            
          ]
        },
        {
          path: 'signpublication',
          meta: { label: 'SignPublication'},
          component: {
            render (c) {return c('router-view')}
          },
          children: [
            {
              path: '',
              component: PublishedSigns,
              meta: {
                requiresAdmin: false
              }
            },
            {
              path: '/signpublication/add',
              component: Signpublication,
              meta: {
                requiresAdmin: false
              }
            },
          ]
        },
        {
          path: 'deploygroups',
          meta: { label: 'DeployGroups'},
          component: {
            render (c) {return c('router-view')}
          },
          children: [
            {
              path: '',
              component: DeployGroups,
              meta: {
                requiresAdmin: false
              }
            },
            {
              path: '/deploygroups/user',
              component: DeployGroupsUser,
              meta: {
                requiresAdmin: false

              }
            },
            {
              path: '/deploygroups/add',
              component: NewDeploy,
              meta: {
                requiresAdmin: false

              }
            }
          ]
        },
        {
          path: 'factory',
          meta: { label: 'Factory' },
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '/factory/signstomake',
              component: FactorySignsToMake,
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
          path: 'user/add',
          meta: {label: 'Insert User'},
          name: 'Insert User',
          component: {
            render (c) { return c('router-view') }
          },
          children: [
            {
              path: '',
              meta: { label: 'Insert User'},
              name: 'InsertUser',
              component: InsertUser,
              meta:{
                requiresAdmin: true
              }
            }
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
        /* {
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
        }*/
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

