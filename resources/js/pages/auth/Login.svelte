<script module>
    import Auth from '@/layouts/Auth.svelte';

    export const layout = (h, page) => {
        return h(Auth, { title: 'Sign in', heading: 'Sign in', footer: footer }, [page]);
    };
</script>

<script lang="ts">
    import { Link, useForm } from '@inertiajs/svelte';
    import Input from '@/components/solior/Input.svelte';
    import Field from '@/components/solior/Field.svelte';
    import Label from '@/components/solior/Label.svelte';
    import FieldGroup from '@/components/solior/FieldGroup.svelte';
    import Button from '@/components/solior/Button.svelte';
    import Checkbox from '@/components/solior/Checkbox.svelte';
    import CheckboxField from '@/components/solior/CheckboxField.svelte';

    const form = useForm({
        email: '',
        password: '',
        remember: true,
    });

    function handleSubmit(e: Event) {
        e.preventDefault();

        form.post(route('login'), {
            onFinish: () => form.reset('password'),
        });
    }
</script>

<form onsubmit={handleSubmit}>
    <FieldGroup>
        <Field id="email">
            <Label>Email address</Label>
            <Input type="email" bind:value={form.email} placeholder="Email" required autocomplete="email" />
        </Field>

        <Field id="password">
            <Label>Password</Label>
            <Input type="password" bind:value={form.password} placeholder="Password" required autocomplete="current-password" />
        </Field>

        <CheckboxField id="remember">
            <Checkbox bind:checked={form.remember} />
            <Label>Remember me</Label>
        </CheckboxField>

        <Button type="submit" color="primary" class="w-full!">Sign in</Button>
    </FieldGroup>
</form>

{#snippet footer()}
    <p>
        No account?
        <Link href={route('register')} class="font-medium text-primary-600 hover:underline">Sign up</Link>
    </p>
{/snippet}
