<script setup>
    import { defineAsyncComponent, onMounted, reactive, ref } from 'vue';

    const props = defineProps({
        csrf: String,
        user: Object,
        route_edit_profile: String,
        route_logout: String,
        route_edit_profile: String,
    });

    const Layout = defineAsyncComponent(() => import('../includes/Layout.vue'));

    const personal_data = reactive({
        user_id: props.user.id,
        full_name: '',
        phone: '',
        address: '',
        is_male: 1,
        current_password: '',
        new_password: '',
        confirm_password: '',
    });

    const updateProfile = () => {

        // validate
        if (personal_data.current_password && !personal_data.new_password) {
            alert('Please enter new password');
            return;
        }

        if (personal_data.current_password && (personal_data.new_password && !personal_data.confirm_password)) {
            alert('Please enter confirm password');
            return;
        }

        if (personal_data.current_password && (personal_data.new_password !== personal_data.confirm_password)) {
            alert('Password does not match');
            return;
        }

        let form = new FormData();
        form.append('user_id', personal_data.user_id);
        form.append('full_name', personal_data.full_name);
        form.append('phone', personal_data.phone);
        form.append('address', personal_data.address);
        if (personal_data.current_password) {
            form.append('current_password', personal_data.current_password);
            form.append('new_password', personal_data.new_password);
            form.append('confirm_password', personal_data.confirm_password);
        }

        console.log(personal_data.value);
    }

    onMounted(() => {
        personal_data.full_name = props.user.name;
        personal_data.phone = props.user.personalData?.phone;
        personal_data.address = props.user.personalData?.address;
        personal_data.is_male = props.user.personalData?.is_male || 1;
    });


</script>

<template>
    <Layout :route_edit_profile="route_edit_profile" :route_logout="route_logout" :user="user" :csrf="csrf">
        <div class="flex justify-between items-center bg-primary-50 px-14 py-7">
            <div class="font-bold text-2xl text-white uppercase tracking-widest">PERSONAL DATA</div>
        </div>

        <div class="py-10 container">
            <div class="items-center grid grid-cols-6 mb-4">
                <label for="full_name" class="block col-span-1 mb-2 font-medium text-primary-50 uppercase tracking-widest">Full name</label>
                <input type="text" id="full_name" v-model.lazy="personal_data.full_name" disabled class="block border-primary-50 col-span-5 bg-transparent p-2.5 border rounded-full w-full text-primary-50"/>
            </div>
            <div class="items-center grid grid-cols-6 mb-4">
                <label for="phone" class="block col-span-1 mb-2 font-medium text-primary-50 uppercase tracking-widest">Phone No</label>
                <input type="text" id="phone"
                    v-model.lazy="personal_data.phone"
                    class="block border-primary-50 col-span-5 bg-transparent p-2.5 border rounded-full w-full text-primary-50"/>
            </div>
            <div class="items-center grid grid-cols-6 mb-4">
                <label for="email" class="block col-span-1 mb-2 font-medium text-primary-50 uppercase tracking-widest self-start">Address</label>
                <textarea rows="4" name="" id=""
                    v-model.lazy="personal_data.address"
                    class="block border-primary-50 col-span-5 bg-transparent p-2.5 border rounded-3xl w-full text-primary-50"></textarea>
                <!-- <input type="email" id="email" class="block border-primary-50 col-span-5 bg-transparent p-2.5 border rounded-full w-full text-primary-50"/> -->
            </div>
            <div class="items-center grid grid-cols-6">
                <label class="block col-span-1 mb-2 font-medium text-primary-50 uppercase tracking-widest">gender</label>
                <div class="flex gap-4 col-span-5">
                    <div class="flex items-center">
                        <input id="default-radio-1" v-model="personal_data.is_male" type="radio" value="1" name="default-radio" class="border-gray-300 bg-secondary focus:ring-secondary w-4 h-4 text-secondary-50">
                        <label for="default-radio-1" class="mt-1 text-primary-50 uppercase ms-1">Male</label>
                    </div>
                    <div class="flex items-center">
                        <input id="default-radio-2" v-model="personal_data.is_male" type="radio" value="0" name="default-radio" class="border-gray-300 bg-secondary focus:ring-secondary w-4 h-4 text-secondary-50">
                        <label for="default-radio-2" class="mt-1 text-primary-50 uppercase ms-1">Female</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Change password -->
        <div class="flex justify-between items-center bg-primary-50 px-14 py-7">
            <div class="font-bold text-2xl text-white uppercase tracking-widest">Change password
                <span class="text-base">
                    (Leave blank if you don't want to change password)
                </span>
            </div>
        </div>

        <div class="py-10 container">
            <div class="items-center gap-4 grid grid-cols-8 mb-4">
                <label for="recent-pass" class="block col-span-2 mb-2 font-medium text-primary-50 uppercase tracking-widest">recent password</label>
                <input type="password" v-model="personal_data.current_password" id="recent-pass" class="block border-primary-50 col-span-6 bg-transparent p-2.5 border rounded-full w-full text-primary-50"/>
            </div>
            <div class="items-center gap-4 grid grid-cols-8 mb-4">
                <label for="new-pass" class="block col-span-2 mb-2 font-medium text-primary-50 uppercase tracking-widest">new password</label>
                <input type="text" v-model="personal_data.new_password" id="new-pass" class="block border-primary-50 col-span-6 bg-transparent p-2.5 border rounded-full w-full text-primary-50"/>
            </div>
            <div class="items-center gap-4 grid grid-cols-8 mb-4">
                <label for="confirm-pass" class="block col-span-2 mb-2 font-medium text-primary-50 uppercase tracking-widest">CONFIRM password</label>
                <input type="text" v-model="personal_data.confirm_password" id="confirm-pass" class="block border-primary-50 col-span-6 bg-transparent p-2.5 border rounded-full w-full text-primary-50"/>
            </div>
        </div>

        <div class="float-right">
                <button @click="updateProfile" class="flex items-center gap-2 bg-secondary-50 px-14 py-6 text-white tracking-widest">
                    <span class="uppercase">update</span>
                    <img class="inline-block" src="img/icons/arrw-ck-right.png" alt="">
                </button>
            </div>
    </Layout>
</template>
