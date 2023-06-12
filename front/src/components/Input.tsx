import { Input as MuiJoyInput } from '@mui/joy'
import { forwardRef } from 'react'

type InputProps = {
    name: string
    type?: 'text' | 'password'
    value?: string
}

export const Input = forwardRef<HTMLInputElement, InputProps>((props, ref) => {
    const {
        name,
        type = 'text',
        value
    } = props

    return (
        <MuiJoyInput ref={ref} name={name} type={type} value={value} />
    )
})