<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<xsl:template match="/">
<xsl:for-each select='//root'>
	<xsl:element name='table'>
		<xsl:attribute name='class'>ListTable</xsl:attribute>
		<xsl:attribute name='width'>100%</xsl:attribute>
		<xsl:element name='tr'>
			<xsl:element name='td'>
				<xsl:attribute name='class'>ListHeader</xsl:attribute>
				<xsl:attribute name='width'>50%</xsl:attribute>
				 <xsl:for-each select="headers">
					<xsl:element name='td'>
						<xsl:attribute name='class'>ListColHeaderLeft</xsl:attribute>
						<xsl:attribute name='colspan'>4</xsl:attribute>
						<img src='./img/icones/16x16/text_view.gif'/>
						<xsl:value-of select="."/>
					</xsl:element>
				</xsl:for-each>
			</xsl:element>
			<xsl:element name='td'>
				<xsl:attribute name='class'>ListHeader</xsl:attribute>
				<xsl:attribute name='width'>50%</xsl:attribute>
				<xsl:attribute name='align'>right</xsl:attribute>
				<xsl:for-each select="exports">
					<xsl:for-each select="link">
						<a>
							<xsl:attribute name="href">
								<xsl:value-of match="@url"/>
							</xsl:attribute>
							<img>
								<xsl:attribute name="src">
									<xsl:value-of match="@src"/>
								</xsl:attribute>
								<xsl:attribute name="alt">
									<xsl:value-of match="@alt"/>
								</xsl:attribute>
							</img>
						</a>
					</xsl:for-each>
				</xsl:for-each>
			</xsl:element>
		</xsl:element>
		<xsl:element name='tr'>
			<xsl:attribute name='class'>ListColLeft</xsl:attribute>
			<xsl:element name='td'>
				<xsl:attribute name='align'>center</xsl:attribute>
				<xsl:attribute name='colspan'>6</xsl:attribute>
				<xsl:for-each select="from">
					<xsl:value-of select="."/>
				</xsl:for-each>
				<input type="text">
					<xsl:attribute name='id'>StartDate</xsl:attribute>
					<xsl:attribute name='name'>StartDate</xsl:attribute>
					<xsl:attribute name='size'>10</xsl:attribute>
					<xsl:attribute name='onClick'>displayDatePicker('StartDate', this)</xsl:attribute>
					<xsl:attribute name='value'>
						<xsl:for-each select="StartDate">
							<xsl:value-of select="."/>
						</xsl:for-each>
					</xsl:attribute>
				</input>
				<input type="text">
					<xsl:attribute name='id'>StartTime</xsl:attribute>
					<xsl:attribute name='name'>StartTime</xsl:attribute>
					<xsl:attribute name='size'>4</xsl:attribute>
					<xsl:attribute name='onClick'>displayTimePicker('StartTime', this)</xsl:attribute>
					<xsl:attribute name='value'>
						<xsl:for-each select="StartTime">
							<xsl:value-of select="."/>
						</xsl:for-each>
					</xsl:attribute>
				</input>
				<xsl:for-each select="to">
					<xsl:value-of select="."/>
				</xsl:for-each>
				<input type="text">
					<xsl:attribute name='id'>EndDate</xsl:attribute>
					<xsl:attribute name='name'>EndDate</xsl:attribute>
					<xsl:attribute name='size'>10</xsl:attribute>
					<xsl:attribute name='onClick'>displayDatePicker('EndDate',this);</xsl:attribute>
					<xsl:attribute name='value'>
						<xsl:for-each select="EndDate">
							<xsl:value-of select="."/>
						</xsl:for-each>
					</xsl:attribute>
				</input>
				<input type="text">
					<xsl:attribute name='id'>EndTime</xsl:attribute>
					<xsl:attribute name='name'>EndTime</xsl:attribute>
					<xsl:attribute name='size'>4</xsl:attribute>
					<xsl:attribute name='onClick'>displayTimePicker('EndTime',this);</xsl:attribute>
					<xsl:attribute name='value'>
						<xsl:for-each select="EndTime">
							<xsl:value-of select="."/>
						</xsl:for-each>
					</xsl:attribute>
				</input>
			</xsl:element>
		</xsl:element>
	</xsl:element>
</xsl:for-each>
</xsl:template>
</xsl:stylesheet>