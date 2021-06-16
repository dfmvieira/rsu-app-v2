export default [{
  _name: 'CSidebarNav',
  _children: [
    {
      _name: 'CSidebarNavItem',
      name: 'Dashboard',
      to: '/dashboard',
      icon: 'cil-speedometer',
      badge: {
        color: 'primary',
        text: 'NEW'
      }
    },
    {
      _name: 'CSidebarNavTitle',
      _children: ['Theme']
    },
    {
      _name: 'CSidebarNavItem',
      name: 'Colors',
      to: '/theme/colors',
      icon: 'cil-drop'
    },
    {
      _name: 'CSidebarNavItem',
      name: 'Typography',
      to: '/theme/typography',
      icon: 'cil-pencil'
    },
    {
      _name: 'CSidebarNavTitle',
      _children: ['Components']
    },
    {
      _name: 'CSidebarNavDropdown',
      name: 'Base',
      route: '/base',
      icon: 'cil-puzzle',
      items: [{
          name: 'Breadcrumbs',
          to: '/base/breadcrumbs',
          icon: 'cil-puzzle'
        },
        {
          name: 'Cards',
          to: '/base/cards',
          icon: 'cil-puzzle'
        },
        {
          name: 'Carousels',
          to: '/base/carousels',
          icon: 'cil-puzzle'
        },
        {
          name: 'Collapses',
          to: '/base/collapses',
          icon: 'cil-puzzle'
        },
        {
          name: 'Jumbotrons',
          to: '/base/jumbotrons',
          icon: 'cil-puzzle'
        },
        {
          name: 'List Groups',
          to: '/base/list-groups',
          icon: 'cil-puzzle'
        },
        {
          name: 'Navs',
          to: '/base/navs',
          icon: 'cil-puzzle'
        },
        {
          name: 'Navbars',
          to: '/base/navbars',
          icon: 'cil-puzzle'
        },
        {
          name: 'Paginations',
          to: '/base/paginations',
          icon: 'cil-puzzle'
        },
        {
          name: 'Popovers',
          to: '/base/popovers',
          icon: 'cil-puzzle'
        },
        {
          name: 'Progress Bars',
          to: '/base/progress-bars',
          icon: 'cil-puzzle'
        },
        {
          name: 'Switches',
          to: '/base/switches',
          icon: 'cil-puzzle'
        },
        {
          name: 'Tabs',
          to: '/base/tabs',
          icon: 'cil-puzzle'
        },
        {
          name: 'Tooltips',
          to: '/base/tooltips',
          icon: 'cil-puzzle'
        }
      ]
    },
    {
      _name: 'CSidebarNavDropdown',
      name: 'Buttons',
      route: '/buttons',
      icon: 'cil-cursor',
      items: [{
          name: 'Buttons',
          to: '/buttons/standard-buttons',
          icon: 'cil-cursor'
        },
        {
          name: 'Button Dropdowns',
          to: '/buttons/dropdowns',
          icon: 'cil-cursor'
        },
        {
          name: 'Button Groups',
          to: '/buttons/button-groups',
          icon: 'cil-cursor'
        },
        {
          name: 'Brand Buttons',
          to: '/buttons/brand-buttons',
          icon: 'cil-cursor'
        }
      ]
    },
    {
      _name: 'CSidebarNavItem',
      name: 'Charts',
      to: '/charts',
      icon: 'cil-chart-pie'
    },
    {
      _name: 'CSidebarNavDropdown',
      name: 'Editors',
      route: '/editors',
      icon: 'cil-code',
      items: [
        {
          name: 'Code editors',
          to: '/editors/code-editors',
          icon: 'cil-code',
          badge: {
            color: 'danger',
            text: 'PRO'
          }
        },
        {
          name: 'Text editors',
          to: '/editors/text-editors',
          icon: 'cil-justify-left'
        }
      ]
    },
    {
      _name: 'CSidebarNavDropdown',
      name: 'Forms',
      route: '/forms',
      icon: 'cil-notes',
      items: [
        {
          name: 'Basic forms',
          to: '/forms/basic-forms',
          icon: 'cil-notes',
        },
        {
          name: 'Adcanced forms',
          to: '/forms/advanced-forms',
          icon: 'cil-notes',
          badge: {
            color: 'danger',
            text: 'PRO'
          }
        },
        {
          name: 'Validation forms',
          to: '/forms/validation-forms',
          icon: 'cil-notes',
          badge: {
            color: 'danger',
            text: 'PRO'
          }
        }
      ]
    },
    {
      _name: 'CSidebarNavItem',
      name: 'Google Maps',
      to: '/google-maps',
      icon: 'cil-map',
      badge: {
        color: 'danger',
        text: 'PRO'
      }
    },
    {
      _name: 'CSidebarNavDropdown',
      name: 'Icons',
      route: '/icons',
      icon: 'cil-star',
      items: [{
          name: 'CoreUI Icons',
          to: '/icons/coreui-icons',
          icon: 'cil-star',
          badge: {
            color: 'info',
            text: 'NEW'
          }
        },
        {
          name: 'Brands',
          to: '/icons/brands',
          icon: 'cil-star'
        },
        {
          name: 'Flags',
          to: '/icons/flags',
          icon: 'cil-star'
        }
      ]
    },
    {
      _name: 'CSidebarNavDropdown',
      name: 'Notifications',
      route: '/notifications',
      icon: 'cil-bell',
      items: [{
          name: 'Alerts',
          to: '/notifications/alerts',
          icon: 'cil-bell'
        },
        {
          name: 'Badges',
          to: '/notifications/badges',
          icon: 'cil-bell'
        },
        {
          name: 'Modals',
          to: '/notifications/modals',
          icon: 'cil-bell'
        },
        {
          name: 'Toaster',
          to: '/notifications/toaster',
          icon: 'cil-bell',
          badge: {
            color: 'danger',
            text: 'PRO'
          }
        }
      ]
    },
    {
      _name: 'CSidebarNavDropdown',
      name: 'Plugins',
      route: '/plugins',
      icon: 'cil-input-power',
      items: [
        {
          name: 'Draggable',
          to: '/plugins/draggable',
          icon: 'cil-cursor-move',
          badge: {
            color: 'danger',
            text: 'PRO'
          }
        },
        {
          name: 'Calendar',
          to: '/plugins/calendar',
          icon: 'cil-calendar',
          badge: {
            color: 'danger',
            text: 'PRO'
          }
        },
        {
          name: 'Spinners',
          to: '/plugins/spinners',
          icon: 'cil-circle',
          badge: {
            color: 'danger',
            text: 'PRO'
          }
        }
      ]
    },
    {
      _name: 'CSidebarNavDropdown',
      name: 'Tables',
      route: '/tables',
      icon: 'cil-list',
      items: [
        {
          name: 'Basic Tables',
          to: '/tables/tables',
          icon: 'cil-list',
        },
        {
          name: 'Advanced tables',
          to: '/tables/advanced-tables',
          icon: 'cil-list-rich'
        }  
      ]
    },
    {
      _name: 'CSidebarNavItem',
      name: 'Widgets',
      to: '/widgets',
      icon: 'cil-calculator',
      badge: {
        color: 'primary',
        text: 'NEW',
        shape: 'pill'
      }
    },
    {
      _name: 'CSidebarNavDivider'
    },
    {
      _name: 'CSidebarNavTitle',
      _children: ['Extras']
    },
    {
      _name: 'CSidebarNavDropdown',
      name: 'Pages',
      route: '/pages',
      icon: 'cil-star',
      items: [
        {
          name: 'Login',
          to: '/pages/login',
          icon: 'cil-star'
        },
        {
          name: 'Register',
          to: '/pages/register',
          icon: 'cil-star'
        },
        {
          name: 'Error 404',
          to: '/pages/404',
          icon: 'cil-star'
        },
        {
          name: 'Error 500',
          to: '/pages/500',
          icon: 'cil-star'
        }
      ]
    },
    {
      _name: 'CSidebarNavDropdown',
      name: 'Apps',
      route: '/apps',
      icon: 'cil-layers',
      _children: [
        {
          _name: 'CSidebarNavDropdown',
          name: 'Invoicing',
          route: '/apps/invoicing',
          icon: 'cil-spreadsheet',
          items: [
            {
              name: 'Invoice',
              to: '/apps/invoicing/invoice',
              icon: 'cil-spreadsheet',
              badge: {
                color: 'danger',
                text: 'PRO'
              }
            }
          ]
        },
        {
          _name: 'CSidebarNavDropdown',
          name: 'Email',
          route: '/apps/email',
          icon: 'cil-envelope-closed',
          items: [
            {
              name: 'Inbox',
              to: '/apps/email/inbox',
              icon: 'cil-envelope-closed',
              badge: {
                color: 'danger',
                text: 'PRO'
              }
            },
            {
              name: 'Message',
              to: '/apps/email/message',
              icon: 'cil-envelope-open',
              badge: {
                color: 'danger',
                text: 'PRO'
              }
            },
            {
              name: 'Compose',
              to: '/apps/email/compose',
              icon: 'cil-envelope-letter',
              badge: {
                color: 'danger',
                text: 'PRO'
              }
            }
          ]
        }
      ]
    },
    {
      _name: 'CSidebarNavDivider',
      _class: 'm-2'
    },
    {
      _name: 'CSidebarNavTitle',
      _children: ['Labels']
    },
    {
      _name: 'CSidebarNavItem',
      name: 'Label danger',
      icon: {
        name: 'cil-star',
        class: 'text-danger'
      },
      label: true
    },
    {
      _name: 'CSidebarNavItem',
      name: 'Label info',
      icon: {
        name: 'cil-star',
        class: 'text-info'
      },
      label: true
    },
    {
      _name: 'CSidebarNavItem',
      name: 'Label warning',
      icon: {
        name: 'cil-star',
        class: 'text-warning'
      },
      label: true
    }
  ]
}]

