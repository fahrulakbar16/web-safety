<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
import Detail from "@/Pages/Profile/Detail.vue";

const props = defineProps({
  confirmsTwoFactorAuthentication: Boolean,
  sessions: Array,
  driver: Object,
  companies: Array,
  isAssessmentPassed: Boolean,
});
</script>

<template>
  <AppLayout title="Profile">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Profile</h2>
    </template>

    <div>
      <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Pakai Detail.vue sebagai tampilan + editor profil + biodata driver -->
        <div v-if="$page.props.jetstream.canUpdateProfileInformation">
          <Detail :user="$page.props.auth.user" :driver="driver" :companies="companies" :is-assessment-passed="isAssessmentPassed" />
          <SectionBorder />
        </div>

        <!-- 2FA -->
        <!-- <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
          <TwoFactorAuthenticationForm
            :requires-confirmation="confirmsTwoFactorAuthentication"
            class="mt-10 sm:mt-0"
          />
          <SectionBorder />
        </div> -->

        <!-- Sesi browser lain -->
        <!-- <LogoutOtherBrowserSessionsForm
          :sessions="sessions"
          class="mt-10 sm:mt-0"
        /> -->

        <!-- Hapus akun -->
        <!-- <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
          <SectionBorder />
          <DeleteUserForm class="mt-10 sm:mt-0" />
        </template> -->
      </div>
    </div>
  </AppLayout>
</template>
