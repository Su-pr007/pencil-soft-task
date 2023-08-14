import { Notify } from 'quasar';

Notify.setDefaults({
  position: 'top-right',
  timeout: 1000,
  textColor: 'white',
  actions: [{ icon: 'close', color: 'white' }]
})