import { Grid as MuiJoyGrid } from '@mui/joy'
import { ReactNode, forwardRef } from 'react'


type GridSize = 'auto' | number
type Breakpoint = 'xs' | 'sm' | 'md' | 'lg' | 'xl'
type ResponsiveStyleValue<T> = T | Array<T | null> | {
    [key in Breakpoint]?: T | null;
}
interface GridBreakpoints {
    lg?: boolean | GridSize;
    lgOffset?: GridSize;
    md?: boolean | GridSize;
    mdOffset?: GridSize;
    sm?: boolean | GridSize;
    smOffset?: GridSize;
    xl?: boolean | GridSize;
    xlOffset?: GridSize;
    xs?: boolean | GridSize;
    xsOffset?: GridSize;
}
type GridSpacing = number | string
type GridDirection = 'row' | 'row-reverse' | 'column' | 'column-reverse'
type GridWrap = 'nowrap' | 'wrap' | 'wrap-reverse'
interface GridProps extends GridBreakpoints {
    children?: ReactNode;
    columns?: ResponsiveStyleValue<number>;
    columnSpacing?: ResponsiveStyleValue<GridSpacing>;
    container?: boolean;
    direction?: ResponsiveStyleValue<GridDirection>;
    rowSpacing?: ResponsiveStyleValue<GridSpacing>;
    spacing?: ResponsiveStyleValue<GridSpacing> | undefined;
    wrap?: GridWrap;
}

export const Grid = forwardRef<HTMLDivElement, GridProps>((props, ref) => {
    const {
        children,
        columns = 12,
        columnSpacing,
        container = false,
        direction = 'row',
        rowSpacing,
        spacing = 0,
        wrap = 'wrap',
        ...gridProps
    } = props

    return (
        <MuiJoyGrid ref={ref} columns={columns} columnSpacing={columnSpacing} container={container} direction={direction} rowSpacing={rowSpacing} spacing={spacing} wrap={wrap} {...gridProps}>
            {children}
        </MuiJoyGrid>
    )
})