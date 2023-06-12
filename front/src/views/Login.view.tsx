import { FormEvent } from 'react'
import { AuthService } from 'src/services/auth.service'
import { LoginDTO } from '@application/useCases/Login/LoginDTO'
import { Button, Grid, Input } from 'src/components'

interface FormElements extends HTMLFormControlsCollection {
    email: HTMLInputElement
    password: HTMLInputElement
}

interface LoginFormElement extends HTMLFormElement {
    readonly elements: FormElements
}

export const LoginView = () => {

    const handleSubmit = async (e: FormEvent<LoginFormElement>) => {
        e.preventDefault()
        const values: LoginDTO = {
            email: e.currentTarget.elements.email.value,
            password: e.currentTarget.elements.password.value,
        }
        const res = await AuthService.doAuth(values)
        console.log(res)
    }

    return (
        <form onSubmit={handleSubmit}>
            <Grid container spacing={2}>
                <Grid xs={8}>
                    <Input name="email" value="my@hotmail.com" />
                </Grid>
                <Grid xs={8}>
                    <Input name="password" type="password" value="password" />
                </Grid>
                <Grid xs={8}>
                    <Button type="submit">Login</Button>
                </Grid>
            </Grid>
        </form>
    )
}