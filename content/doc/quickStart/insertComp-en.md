<!--Prev newProject--> <!--Next insertNet-->
<!--Zoomed true-->
# Inserting components from the library into the schematic
## Enabling component insertion mode
To enable component insertion mode, click this button.

![Component insertion mode button into schematic](shot-1-stp-1-en.png)

## Searching for a component in the database
A dialog box for searching components in the library appears. In the object
search field, enter "xpt" and click the Find button.

![Entering component name for search and Find button](shot-1-stp-2-en.png)

A list of components matching the search string appears. Select the
XPT8871 chip from this list.

![Selecting a component from the search list](shot-1-stp-3-en.png)

A preview for the selected component will appear at the bottom of the search window:
its list of sections, and the image of the current section will be shown in the Symbol window. In the
Package window, a graphical image of the component's package will be shown. In the 3D window - a three-dimensional
image of the component's package.

### Selecting a component section from the section list
Select the desired section from the list of available component sections.
If the component has one section, it is selected automatically.

![Selecting a component section from the section list](shot-1-stp-4-en.png)

Click OK to insert the selected section of the selected component into the schematic.

![Completing component selection](shot-1-stp-5-en.png)

## Setting the component position on the schematic
Move the mouse across the schematic sheet area. The component will move with the mouse.

![Setting the component position on the schematic](shot-1-stp-6-en.png)

When you determine the desired component position, press the left mouse button. The
component is inserted into the schematic and you can determine the position for the next instance
of the component.

![Inserting a component into the schematic](shot-1-stp-7-en.png)

Press the right mouse button to stop inserting the current component and switch
to the ability to select another component.

![Inserting a component into the schematic](shot-1-stp-8-en.png)

## Searching for and inserting a capacitor
In the component search field, enter "0603 1uf". This query means
that all components that have both 0603 and 1uf in their name or parameters will be found.

![Entering name for capacitor search](shot-1-stp-9-en.png)

A large list will appear, which will contain capacitors with capacitance 0.1uf,
0.01uf and 1uf. This is normal and happens because the search is performed by part
of the name or parameter. Keep this in mind when specifying the search pattern.
Find the capacitor with exactly 1uf capacitance in the list and click on it with the left mouse button.

![Selecting 1uf capacitor from the list of found capacitors](shot-1-stp-10-en.png)

Click OK to insert the capacitor into the schematic.

![Inserting capacitor](shot-1-stp-11-en.png)

### Determining capacitor placement
Move the mouse to determine the desired capacitor position. Press the left mouse button to insert the capacitor into the schematic.

![Determining capacitor placement](shot-1-stp-12-en.png)

### Component orientation
You can insert several instances of the same component. In this case, the base orientation
of the component does not match the desired one. Change the component orientation using the orientation buttons.

![Changing capacitor orientation](shot-1-stp-13-en.png)

Determine the location for the capacitor and press the left mouse button.

![Inserting the second instance of the capacitor](shot-1-stp-14-en.png)

Determine the location for the third instance of the capacitor and press the left mouse button.

![Inserting the third instance of the capacitor](shot-1-stp-15-en.png)

All instances of the 1uf capacitor have been inserted. Press the right mouse button to return to component selection.

![All capacitor instances inserted](shot-1-stp-16-en.png)

## Searching for and inserting a resistor
In the search field, enter "0603 1k" and click the Search button.

![Entering resistor in search field](shot-1-stp-17-en.png)

Among all found resistors, select the resistor with 1kOhm rating.

![Entering resistor in search field](shot-1-stp-18-en.png)

And click OK to place and insert.

![Entering resistor in search field](shot-1-stp-19-en.png)

The orientation for the resistor remained from the previous component. It is incorrect.
Change the orientation to the correct one.

![Changing resistor orientation](shot-1-stp-20-en.png)

Specify the location for the resistor and press the left mouse button. Since only one resistor with this rating is needed, then press the right mouse button to switch to searching for another rating.

![Inserting resistor](shot-1-stp-21-en.png)

## Searching for and inserting a resistor with 4.7kOhm rating
In the search field, enter "0603 4.7k" and click Find.

![Searching for resistor with 4.7kOhm rating](shot-1-stp-22-en.png)

In the list of found resistors, select the resistor with 4.7kOhm rating.

![Selecting resistor with 4.7kOhm rating from list](shot-1-stp-23-en.png)

Click OK to place the resistor in the schematic.

![Completing resistor selection](shot-1-stp-24-en.png)

Move the mouse to indicate the resistor's location on the schematic. Press the left mouse button to insert the resistor into the schematic.

![Inserting resistor](shot-1-stp-25-en.png)

Press the right mouse button to return to searching for the next component.

![Completing resistor insertion](shot-1-stp-26-en.png)

## Searching for and inserting a connector
In the component search field, enter "pls3" and click Find.

![Searching for connector](shot-1-stp-27-en.png)

In the appearing list of suitable connectors, select connector pls3

![Selecting connector from list](shot-1-stp-28-en.png)

To insert the component into the schematic, click OK

![Completing connector search](shot-1-stp-29-en.png)

### Component mirroring
The base image of the connector is not suitable - the connector has the wrong direction.

![Base connector direction](shot-1-stp-30-en.png)

This cannot be fixed with orientation (the connector will be upside down). This requires mirroring the component. Change the component mirroring with this button.

![Connector mirroring](shot-1-stp-31-en.png)

Specify the connector location on the schematic and press the left mouse button.

![Specifying connector location](shot-1-stp-32-en.png)

Press the right mouse button to return to component search.

![Completing connector insertion](shot-1-stp-33-en.png)

## Searching for and inserting pls2 connector
In the component search field, enter "pls2" and click the Find button.

![Searching for pls2 connector](shot-1-stp-34-en.png)

In the appearing list of suitable connectors, select connector pls2

![Selecting connector from list](shot-1-stp-35-en.png)

To insert the component into the schematic, click OK

![Completing connector search](shot-1-stp-36-en.png)

The mirroring of this component remained from the previous one.

![Connector mirroring](shot-1-stp-37-en.png)

Mirroring is no longer needed for this connector, so disable it.

![Disabling connector mirroring](shot-1-stp-38-en.png)

Specify the connector location on the schematic and press the left mouse button.
Press the right mouse button to return to component search.

![Specifying connector location](shot-1-stp-39-en.png)

## Searching for and inserting a capacitor
Enter "16v 10uf" in the search and click the Find button.

![Searching for capacitor](shot-1-stp-40-en.png)

In the appearing list of suitable connectors, select connector capacitor leaded

![Selecting capacitor from list](shot-1-stp-41-en.png)

To insert the component into the schematic, click OK

![Completing capacitor search](shot-1-stp-42-en.png)

Place the capacitor on the schematic and press the left mouse button.

![Completing component insertion](shot-1-stp-43-en.png)

## Exiting component insertion mode
To stop inserting components, press the right mouse button.

![Completing component insertion](shot-1-stp-44-en.png)

The component search dialog box appears. Click Cancel in it. This will exit component insertion mode.

![Completing component insertion](shot-1-stp-45-en.png)

## Result of component insertion
After inserting all components, the schematic should look similar to this.

![Completing component insertion](shot-1-stp-46-en.png)
