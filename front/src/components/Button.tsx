import { Button as MuiJoyButton } from '@mui/joy'
import { ReactNode, forwardRef } from 'react'

type ButtonProps = {
    type?: 'submit' | 'button'
    children: ReactNode
}

export const Button = forwardRef<HTMLButtonElement, ButtonProps>((props, ref) => {
    const {
        type = 'button',
        children
    } = props

    return (
        <MuiJoyButton ref={ref} type={type}>{children}</MuiJoyButton>
    )
})