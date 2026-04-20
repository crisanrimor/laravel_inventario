import { usePage } from '@inertiajs/vue3'

export function hasPermission(permission){
    const page = usePage();
    return page.props.auth.permissions.includes(permission);
}
