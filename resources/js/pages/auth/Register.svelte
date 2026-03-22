<script module>
    import Auth from '@/layouts/Auth.svelte';

    export const layout = (h, page) => {
        return h(Auth, { title: 'Sign up', heading: 'Create an account', footer: footer }, [page]);
    };
</script>

<script lang="ts">
    import { Link, useForm } from '@inertiajs/svelte';
    import FieldGroup from '../../components/solior/FieldGroup.svelte';
    import Field from '../../components/solior/Field.svelte';
    import Label from '../../components/solior/Label.svelte';
    import Input from '../../components/solior/Input.svelte';
    import Button from '../../components/solior/Button.svelte';

    const form = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    function handleSubmit(e: Event) {
        e.preventDefault();

        $form.post(route('register'), {
            onFinish: () => $form.reset('password', 'password_confirmation'),
        });
    }
</script>

<form onsubmit={handleSubmit}>
    <FieldGroup>
        <Field id="name">
            <Label>Name</Label>
            <Input bind:value={$form.name} placeholder="Name" required />
        </Field>

        <Field id="email">
            <Label>Email address</Label>
            <Input type="email" bind:value={$form.email} placeholder="Email" required autocomplete="email" />
        </Field>

        <Field id="password">
            <Label>Password</Label>
            <Input type="password" bind:value={$form.password} placeholder="Password" required autocomplete="new-password" />
        </Field>

        <Field id="confirm-password">
            <Label>Confirm password</Label>
            <Input type="password" bind:value={$form.password_confirmation} placeholder="Confirm password" required autocomplete="new-password" />
        </Field>

        <Button type="submit" color="primary" class="w-full!">Create account</Button>
    </FieldGroup>
</form>

{#snippet footer()}
    <p>
        Already have an account?
        <Link href={route('login')} class="font-medium text-primary-600 hover:underline">Sign in</Link>
    </p>
{/snippet}
