import Vue from 'vue'

export const EventBus = new Vue({
    methods: {
        notyfy(evantName, eventParams) {
            this.$emit(evantName, eventParams)
        }
    }
});

export function setupBus() {
    Object.defineProperties(Vue.prototype, {
        $bus: {
            get: function () {
                return EventBus
            }
        }
    })
}
