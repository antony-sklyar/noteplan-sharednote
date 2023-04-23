<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';

defineProps({
    formUrl: String
});
</script>

<template>
    <Head title="Password Required" />

    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="flex justify-center">
                <img src="/icon.png" class="h-20 md:h-40">
            </div>

            <form @submit.prevent="sendPassword" class="mt-16">
              <label class="block">
                <span class="block text-sm font-medium text-slate-700 dark:text-slate-400">
                    Please provide a password to view this note content
                </span>

                <input v-model="form.password" type="password" class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
                  focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500
                  disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
                  invalid:border-pink-500 invalid:text-pink-600
                  focus:invalid:border-pink-500 focus:invalid:ring-pink-500
                "/>

                <div v-if="form.errors.password" class="mt-3 text-red-500 text-xs">{{ form.errors.password }}</div>
              </label>

              <div class="block mt-6 flex justify-center">
                  <button type="submit" :disabled="form.processing" class="bg-orange-400 hover:bg-orange-500 px-5 py-2.5 text-sm leading-5 rounded-md font-semibold text-white">
                    Open Note
                  </button>
              </div>
            </form>

            <div class="flex justify-center mt-16 px-6 items-center">
                <div class="text-center text-sm text-gray-500 dark:text-gray-400 sm:text-left">
                    <div class="flex items-center gap-4">
                        <a href="https://noteplan.co" class="group inline-flex items-center hover:text-gray-700 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                            Powered by NotePlan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
  data() {
    return {
      form: useForm({ password:null })
    }
  },
  methods: {
    sendPassword() {
      console.log(this.formUrl);
      this.form.post(this.formUrl, {
        onSuccess: (response) => console.log('success', response)
      });
    }
  }
}
</script>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
