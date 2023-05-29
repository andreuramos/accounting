import { useEffect } from 'react'
import { AuthService } from 'src/services/auth.service'


const credentials = {
	"email": "my@hotmail.com",
	"password": "password"
}


export const DashboardView = () => {
    useEffect(() => {
        ;(async () => {
            AuthService.doAuth(credentials)
        })()
    }, [])

    return (
        <>dashboard</>
    )
}